<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<h1 align="center"><a href="https://frankenphp.dev"><img src="frankenphp.png" alt="FrankenPHP" width="400"></a></h1>

# Getting Started

## Настройка окружения

1. Скопируйте файл окружения:
```bash
cp .env.example .env
```
2. Настройте необходимые переменные в `.env`:
```bash
APP_NAME=app
APP_NAMESPACE=myapp
```
3. Инициализация проекта:

С использованием Taskfile (https://taskfile.dev/docs/installation#get-the-binary):
```bash
task init
```

Вручную:

```bash
docker-compose up -d --build
```

```bash
docker exec -it myapp-app /bin/bash
```
```bash
composer install
```
```bash
php artisan key:generate
```
```bash
php artisan storage:link
```
```bash
php artisan migrate
```
```bash
php artisan db:seed
```
```bash
exit
```
```bash
docker-compose restart
```

## Taskfile

```bash
task exec
```
```bash
task up
```
```bash
task stop
```
```bash
task tink
```
```bash
task check
```



# Code quality: 
```bash
task check
```
или

```bash
docker exec -it myapp-app /bin/bash
```

```bash
vendor/bin/pint --config ./pint.json
```
```bash
vendor/bin/rector process
```
```bash
vendor/bin/phpinsights --quiet
```
```bash
vendor/bin/phpstan analyse -c ./phpstan.neon
```
```bash
php artisan test
```


# About 

Description
