#!/bin/bash

set -e

if [ "$TRAVIS_BRANCH" == "master" ]; then

    echo "Setting up config"
    openssl aes-256-cbc -K $encrypted_625fb94bd2ee_key -iv $encrypted_625fb94bd2ee_iv -in build/secrets.tar.enc -out secrets.tar -d
    tar xvf secrets.tar
    eval "$(ssh-agent -s)"
    chmod 600 ssh_deploy_key
    ssh-add ssh_deploy_key
    cp ssh_config ~/.ssh/config
    cp known_hosts ~/.ssh/known_hosts

    # Uninstall the dev dependencies
    composer install --no-interaction --prefer-dist --no-dev

    echo "Packaging release"
    tar -zcvf release.tar.gz app artisan bootstrap build database public resources vendor > /dev/null

    echo "Sending files to the remote"
    scp release.tar.gz otto@remote:/tmp

    echo "Run release install on the remote host"
    ssh otto@remote 'mkdir -p /tmp/release && tar -xvzf /tmp/release.tar.gz -C /tmp/release > /dev/null && bash /tmp/release/build/install_release.sh'
fi
