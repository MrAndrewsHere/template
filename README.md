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
cp -n .env.example .env
```
```bash
cp -n .env.testing.example .env.testing
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

## About 

1. Модели и миграции
Создать таблицы:

users (можно взять стандартную от Laravel php artisan make:auth или php artisan migrate).

tasks:

id

user_id (FK на users)

title (string, обязательное)

description (text, необязательное)

status (enum или string: pending, in_progress, done, default pending)

due_date (date, необязательное)

timestamps.

2. Связи
User имеет много Task (hasMany).

Task принадлежит User (belongsTo).

3. API (CRUD)
Сделать REST API (api.php) с роутами:

GET /api/tasks — список задач авторизованного пользователя.

POST /api/tasks — создать задачу.

GET /api/tasks/{id} — получить задачу.

PUT /api/tasks/{id} — обновить задачу.

DELETE /api/tasks/{id} — удалить задачу.

⚠️ Доступ к задачам — только у владельца (авторизация через Auth::id()).

4. Валидация
При создании/обновлении задачи:

title — обязательное, min:3.

status — только pending|in_progress|done.

due_date — date, >= сегодня.

5. Ответ API JSON вида:

{ "id": 1, "title": "Купить хлеб", "status": "pending", "due_date": "2025-09-05", "user": { "id": 1, "name": "Alex" } } 


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

