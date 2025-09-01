# Документация по командам Makefile

## Композитные / Сценарии

- `init` - Инициализировать проект (сборка, установка зависимостей, настройка БД)

## Compose / Оркестрация

- `compose-build` - Собрать и запустить контейнеры в фоновом режиме
- `compose-up` - Запустить контейнеры в фоновом режиме и удалить осиротевшие
- `compose-restart` - Перезапустить все контейнеры
- `compose-stop` - Остановить все контейнеры
- `compose-down` - Остановить и удалить контейнеры, тома

Алиасы:

- `build` → `compose-build`
- `up` → `compose-up`
- `up-prod` → `compose-up-prod`
- `restart` → `compose-restart`
- `stop` → `compose-stop`
- `down` → `compose-down`

## Приложение / Контейнер

- `app-shell` - Открыть оболочку в контейнере приложения
- `app-composer-install` - Установить PHP зависимости
- `app-key-generate` - Сгенерировать ключ приложения
- `app-storage-link` - Создать символическую ссылку на хранилище
- `app-cache-clear` - Очистить кэш приложения
- `app-horizon-install` - Установить Laravel Horizon

Алиасы:

- `exec` → `app-shell`
- `composer-install` → `app-composer-install`
- `key-generate` → `app-key-generate`
- `storage-link` → `app-storage-link`

## База данных

- `db-migrate` - Запустить миграции базы данных
- `db-seed` - Заполнить базу данных начальными данными
- `db-setup` - Запустить миграции и заполнение

## Качество / CI

- `quality-pint-fix` - Исправить стиль кода с помощью Laravel Pint
- `quality-pint-check` - Проверить стиль кода без исправлений
- `quality-rector` - Запустить Rector для рефакторинга кода
- `quality-insights` - Запустить анализ PHP Insights
- `quality-stan` - Запустить статический анализ PHPStan
- `quality-test` - Запустить тесты параллельно
- `quality-all` - Запустить все проверки качества

Алиасы:

- `check` → `quality-all`
- `pint` → `quality-pint-fix`
- `pint-test` → `quality-pint-check`
- `rector` → `quality-rector`
- `insights` → `quality-insights`
- `stan` → `quality-stan`
- `test` → `quality-test`

## Утилиты

- `echo` - Отобразить приветственное сообщение и конфигурацию
- `tink` - Запустить Laravel Tinker
- `swagger` - Сгенерировать документацию Swagger
