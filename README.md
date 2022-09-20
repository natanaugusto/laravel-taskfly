# Laravel Taskfly

This is just a Laravel playground.

## Running locally

This project uses [Laravel Sail](https://laravel.com/docs/9.x/sail) which is a good and easy tool to deploy Laravel applications for development purposes.

I create an [alpine/Dockerfile](docker/alpine/Dockerfile). The default docker image is an Ubuntu. But, I like the [Alpine](image) a little more when we talk about docker image containers.

### To consider

We'll need to clone the [repository](https://github.com/natanaugusto/laravel-taskfly) and after that, We'll run docker-compose and the other commands to run this project locally.

(Certify if you have [docker](https://docs.docker.com/engine/install) and [docker-compose](https://docs.docker.com/compose/install/) correctly installed)

### Let's start
The fellow commands will clone, start and deploy a development instance for this project.

```shell
$ git clone https://github.com/natanaugusto/laravel-taskfly
$ cd laravel-taskfly

$ cp .env.example .env
$ sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mariadb/g' .env
$ sed -i 's/DB_DATABASE=laravel/DB_DATABASE=taskfly/g' .env
$ sed -i 's/DB_USERNAME=root/DB_USERNAME=sail/g' .env
$ sed -i 's/DB_PASSWORD=/DB_PASSWORD=password/g' .env

$ docker-compose up -d
# If you get an error here, just try again one more time to be sure
$ sudo chown $USER:$USER -R vendor
$ docker-compose exec -u sail laravel.test composer install --verbose
$ docker-compose down

$ sail build
$ sail up -d
$ sail artisan key:generate
$ sail artisan migrate
$ sail npm install --verbose
$ sail npm run build
$ sail test
```
### Creating dummy data
We'll use [Laravel Tinker](https://laravel.com/docs/9.x/artisan#tinker) to create fake data. This will create 200 tasks on the database.

```shell
$ sail tinker

Psy Shell v0.11.8 (PHP 8.1.9 — cli) by Justin Hileman
>>> Task::factory(200)->create()
```
### If all goes right

Our local instance is up and filled with dummy data.

- [Register a user](http://localhost/register)
- [Login](http://localhost/register)

## Maybe Stack
- Laravel
  - Socialite
  - Sail
  - Livewire
- Pest
- Laravel Permission
- Swagger/OpenAPI
- ?Laravel Repository
- ?Laravel Module
