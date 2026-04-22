# Blog Smarty (PHP + MySQL)

Блог на:

- PHP 8.1+
- Smarty
- MySQL

## Что реализовано

- Главная страница:
  - вывод категорий, в которых есть статьи
  - 3 последних статьи в каждой категории
  - кнопка `Все статьи` для перехода в категорию
- Страница категории:
  - название, описание, список статей
  - сортировка по дате публикации и по просмотрам
  - пагинация
- Страница статьи:
  - полная информация о статье
  - увеличение счетчика просмотров
  - блок из 3 похожих статей (по общим категориям)
- Сидер категорий и статей
- Использование SCSS:
  - исходники в `assets/scss/style.scss`
  - сборка в `public/assets/css/style.css` через `sass`
- Docker-окружение

## Структура

- `public/` - веб-корень (`index.php`, CSS)
- `src/` - контроллеры, репозитории, bootstrap, seeder
- `templates/` - Smarty-шаблоны
- `config/` - конфиг приложения
- `sql/schema.sql` - схема БД
- `bin/seed.php` - запуск сидера

## Локальный запуск

1. Установить зависимости:
   - `composer install`
   - `npm install`
2. Скопировать env:
   - `copy .env.example .env` (Windows) или `cp .env.example .env`
3. Создать БД и таблицы:
   - импортировать `sql/schema.sql` в MySQL
4. Заполнить тестовыми данными:
   - `php bin/seed.php`
5. Запустить сервер из корня проекта:
   - `php -S localhost:8000 -t public`
6. Собрать CSS из SCSS:
   - `npm run build:css`

Для разработки стилей:
- `npm run watch:css`

Открыть: `http://localhost:8000`

## Docker запуск

1. `docker compose up -d --build`
2. Приложение: `http://localhost:8080`
3. После поднятия контейнеров выполнить сидинг:
   - `docker compose exec app php bin/seed.php`
4. Если меняли SCSS, пересоберите CSS:
   - `docker compose exec app npm run build:css`

CSS из SCSS собирается на этапе `docker build` (и вручную командой выше при изменениях).
