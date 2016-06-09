# Kantoortuin

Your friendly local office server will aid you in your day to day office live

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
- `docker-compose up -d  nginx mysql`
- use `docker-machine ip` to find out the docker IP address (e.g. for setting the db host)
- Enter the virtual machine: `docker exec -it {Workspace-Container-Name} bash` (check `docker-compose ps`)

Next:

- `cp .env.example .env` and update accordingly
- `composer install`
- `php artisan migrate`
- `npm install`
- `gulp compile`

## Run in production

TODO, don't use the dev method, it's too heavy on the rpi. Should be something like
- compile all assets in prod mode
- copy over the files
- run migrations

## License

The Kantoortuin is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
