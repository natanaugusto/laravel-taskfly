# Laravel Taskfly

This project intends to provide a taskfly system where the tasks can be treated like anything.

## Installing this project
First of all, clone this repository.

```bash
git clone https://github.com/natanaugusto/laravel-taskfly
```

This project uses [Laravel Sail](https://laravel.com/docs/9.x/sail) so the Docker Development Environment was provided by that.

(I assumed you have docker already installed on your S.O)

### Copy and configure DotEnv file
```bash
cp .env.example .env
sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=pgsql/g' .env
sed -i 's/DB_HOST=127.0.0.1/DB_HOST=pgsql/g' .env
sed -i 's/DB_PORT=3306/DB_PORT=5432/g' .env
sed -i 's/DB_USERNAME=root/DB_USERNAME=sail/g' .env
sed -i 's/DB_PASSWORD=/DB_PASSWORD=password/g' .env
sed -i 's/MEMCACHED_HOST=127.0.0.1/MEMCACHED_HOST=memcached/g' .env
sed -i 's/REDIS_HOST=127.0.0.1/REDIS_HOST=memcached/g' .env
```

### Download and up the docker containers
```shell
docker-compose up -d
sudo chown $USER:$USER -R vendor
docker-compose exec -u sail laravel.test composer install --verbose
docker-compose down
```
### Using Sail
[Added the sail alias](https://laravel.com/docs/9.x/sail#configuring-a-shell-alias)

```shell
sail build
sail up -d
sail artisan key:generate
sail artisan migrate
sail npm install --verbose
sail npm run build
```

### Test if works
First, lets try if the `tests` runs green.
```shell
sail test
```
All green?

So, let's access the [localhost](http://localhost) to check if is working.
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
