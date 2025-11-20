<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<h1 align="center"><a href="https://frankenphp.dev"><img src="frankenphp.png" alt="FrankenPHP" width="400"></a></h1>


(http://127.0.0.1/)

Stack:
- Laravel (Octane/FrankenPHP) 
- PostgreSQL
- Redis

Dashboards:
- Pulse (http://127.0.0.1/pulse)
- Horizon (http://127.0.0.1/horizon/dashboard)
- Telescope (http://127.0.0.1/telescope/requests)

# Getting Started

## Настройка окружения

1. Скопируйте файл окружения:
```bash
cp .env.example .env --update=none
```
```bash
cp .env.testing.example .env.testing --update=none
```
2. Настройте необходимые переменные в `.env`:
```bash
APP_NAMESPACE=value # value - префикс к сервисам docker-compose 
```
3. Инициализация проекта:

Makefile:
```bash
make init
```
Taskfile (https://taskfile.dev/docs/installation#get-the-binary):
```bash
task init
```


## Commonly used tasks

```bash
make/task exec # контейнер laravel
```
```bash
make/task up
```
```bash
make/task stop
```
```bash
make/task tink
```
```bash
make/task check # проверка качества кода
```



# Code quality: 
```bash
make check
```
или
```bash
task check
```

# About 



Description
