# Kantoortuin

[![Build Status](https://travis-ci.org/orottier/local-office-server.svg?branch=master)](https://travis-ci.org/orottier/local-office-server)

Your friendly local office server will aid you in your day to day office life

It's a next generation sentient machine that enjoys talking to other services and devices to put some synergy in your depressing workplace.
- Sonos
- Check in system
- Slack
- Third party APIs

## Features

- Auto check in if your laptop connects to the network
- Tells you which song is playing

Todo:
- Plan your cocktail Friday
- Get notified if the boss enters the office
- Play songs at given times (lunch tune, cocktail tune)
- Play a song at given events (boss enters the office)
- Connect with barcode reader to order supplies from grocery store

## Get started with your dev environment

Option 1: if you have a webserver, PHP, some database service, npm and composer running locally
- clone this repo
- point your webserver to the /public folder

Option 2: using Docker to run the components
- Install and run Docker
- clone this repo with submodules: `git clone --recursive ...`
- in the `laradock` folder: `docker-compose up -d  nginx mysql`
- use `docker-machine ip` to find out the docker IP address (e.g. for setting the db host)
- Enter the virtual machine: `docker exec -it {Workspace-Container-Name} bash` (check `docker-compose ps`)

Next:

- `cp .env.example .env` and update accordingly
- `composer install`
- `php artisan migrate`
- `npm install`
- `gulp compile`

## Run in production

This repo comes with a `.travis.yml` file to set up continuous integration. Pushing work to master will deploy code to the office raspberry py:
- Travis will clone the repo, install dependecies and run tests
- on success, `deploy.sh` will run
    - package the files needed for running the app
    - copy it to the server
- then `install_release.sh` will be run on the office server
    - move the app files to the right place
    - make sure it's runnable by setting env and database
    - run migrations
    - reload apache

The deploy process depends on an encrypted travis file: `secrets.tar`, containing
 - a private key to log in to the server (`ssh_deploy_key`, make sure you add the pubkey to the `authorized_keys` on the server)
 - a `known_hosts` file with the signature of the server
 - a ssh config file, with the server host and port (called `ssh_config`, containing Host `remote`)

## License

The Kantoortuin is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
