<?php

/** @noinspection LaravelFunctionsInspection */

return [

    /*
    |--------------------------------------------------------------------------
    | Default supported localizations
    |--------------------------------------------------------------------------
    |
    | This list allow supported locales. Add another one if you need extend locales list.
    |
    */

    'languages' => [
        'ru' => 'ru-RU',
        'en' => 'en-US',
    ],

    'current_database_connection' => env('DB_CONNECTION', config('database.default'))
];
