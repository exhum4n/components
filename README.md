# Руководство
[Components](https://github.com/exhum4n/components) это надстройка над фреймворком [Laravel](https://laravel.com/) которая предназначена для изменения файловой архитектуры фреймворка, упрощения взаимодействия с кодом проекта и более гибкой разработки.
Компонент расширяет возможности фреймворка, вносит правки в уже существующие механики, при этом не увеличивая уровень вхождения в него.

## Версии компонентов
- [PHP](https://www.php.net/releases/8.1/en.php) 8.1
- [Laravel](https://laravel.com/) 8+

## Установка

Для начала вам нужно установить [exhum4n/components](https://github.com/exhum4n/components) выполнив команду из корня проекта.

``` bash
composer require exhum4n/components
```

В файле ``composer.json`` необходимо добавить пространство имен ``"Components\\": "components/"`` в секции ``autoload/psr-4``

``` bash
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Components\\": "components/"
        },
```

## Быстрый старт

Для того что-бы начать пользоваться новой архитектурой, нам нужно создать новый компонент, использую команду

``` bash
php artisan components:make 'Example' 
```

После чего в корне проекта появится новая директория ``components`` содержащая следующую структуру

```
Example
├─ Broadcasting
│  ├─ Events
│  └─ Listeners
├─ Commands
├─ Data
├─ Database
│  ├─ Factories
│  ├─ Migrations
│  └─ Seeds
├─ Enums
├─ Exceptions
├─ Jobs
├─ Models
│  └─ Casts
├─ Providers
│  └─ ExampleServiceProvider.php
├─ Repositories
├─ Routes
├─ Traits
└─ Services

```

Все директории который могут не пригодиться вы можете удалить.

Для активации компонента необходимо подключить его в файле ``config/app.php`` в секции ``Providers`` 
сервис провайдер который находится в директории ``Providers`` нового компонента.

```
    'providers' => [
        /*
         * Package Service Providers...
         */
         Components\Example\Providers\ExampleServiceProvider.php <---
    ],
```

Теперь компонент подключен к фреймворку и готов к использованию.
