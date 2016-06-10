#!/bin/bash

set -e

if [ "$TRAVIS_BRANCH" == "master" ]; then

    openssl aes-256-cbc -K $encrypted_625fb94bd2ee_key -iv $encrypted_625fb94bd2ee_iv -in secrets.tar.enc -out secrets.tar -d
    tar xvf secrets.tar
    eval "$(ssh-agent -s)"
    chmod 600 ssh_deploy_key
    ssh-add ssh_deploy_key
    cp ssh_config ~/.ssh/config
    cp known_hosts ~/.ssh/known_hosts

    # Skip the dev dependencies
    composer install --no-interaction --prefer-dist --no-dev
    tar -zcvf release.tar.gz app artisan bootstrap database install_release.sh public resources vendor

    scp release.tar.gz otto@remote:/tmp
    ssh otto@remote 'mkdir -p /tmp/release && tar -xvzf /tmp/release.tar.gz -C /tmp/release > /dev/null && bash /tmp/release/install_release.sh'
fi
