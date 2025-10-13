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

### API для трекинга задач команды 

Создать простое REST API для управления задачами в команде разработчиков. 
Документация эндпоинтов – по желанию. 
### Требования 
#### 1. База данных (MySQL) 
Создать таблицы: 
- users - пользователи (id, name, email, position) 
position имеет значения: manager, developer, tester 
- tasks - задачи (id, title, description, user_id, status, priority, created_at, updated_at) 
status имеет значения: new, in_progress, completed, cancelled 
priority имеет значения: high, normal, low 
- task_comments - комментарии к задачам (id, task_id, user_id, comment, created_at) 
- task_notifications - уведомления (id, user_id, task_id, message, created_at) 
#### 2. API Endpoints 
#### GET /api/tasks 
- Возвращает список задач 
- Фильтры: status, priority, user_id 
- Сортировка по дате создания (новые первыми) 
#### POST /api/tasks 
- Создание новой задачи 
- Поля: title (обязательно), description, user_id, priority 
- Status по умолчанию = "new" 
- Если user_id не указан, назначить задачу на пользователя с position = "manager" 
#### PUT /api/tasks/{id}/status 
- Изменение статуса задачи 
- Принимает: status (любой из возможных в таблице tasks), user_id 
- При смене статуса на "completed" автоматически добавить комментарий "Task 
completed by [user_name]" 
- При смене статуса запустить job для отправки уведомлений всем пользователям с 
position = "manager" 
#### POST /api/tasks/{id}/comments 
- Добавление комментария к задаче 
- Поля: comment (обязательно), user_id (обязательно) 
#### GET /api/tasks/{id} 
- Получение задачи с комментариями. Если комментариев нет, то в 
соответствующем поле вернуть пустой список. 
- Включить информацию о пользователе (name, position) 
#### 3. Queue Job: SendTaskNotificationJob 
#### Создать job, который: 
- Принимает task_id и notification_type ("status_changed", "task_assigned", "overdue") 
- Находит всех менеджеров (position = "manager") 
- Создает запись в таблице task_notifications для каждого менеджера 
- Симулирует отправку уведомления (достаточно записать в лог) 
#### Когда запускать job: 
- При создании новой задачи с priority = "high" (type: "task_assigned") 
- При изменении статуса любой задачи (type: "status_changed") 
- При запуске artisan команды (см. ниже) 
#### 4. Artisan команда: tasks:check-overdue 
Создать команду php artisan tasks:check-overdue, которая: 
- Находит все задачи со статусом "in_progress", созданные более 7 дней назад 
- Для каждой такой задачи добавляет комментарий: "Task is overdue! Created 
[дата_создания]" 
- Запускает SendTaskNotificationJob с type "overdue" 
- Выводит в консоль количество найденных просроченных задач 
Signature команды: tasks:check-overdue {--dry-run : Show what would be done 
without making changes} 
- С флагом --dry-run только показывать количество, не добавлять комментарии 
#### 5. Бизнес-логика 
Автоматические действия: 
- При создании задачи (не обязательно только через эндпоинт создания) с priority = 
"high" - автоматически назначить status = "in_progress".  
- Нельзя добавлять комментарии к задачам со status = "cancelled" 
#### 6. Дополнительные требования 
- Валидация входящих данных с возвратом понятных ошибок (стандартные ошибки 
фреймворка переопределять не нужно) 
- Все запросы и ответы в формате JSON. Если запрос был сделан не в формате JSON, 
то выдавать ошибку некорректного запроса.

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

