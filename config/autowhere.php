<?php

return [

    'db' => [
        'type'               => env("DB_CONNECTION"), // only accept mysql and pgsql string
        'host'               => env("DB_HOST"),
        'database'           => env("DB_DATABASE"),
        'username'           => env("DB_USERNAME"),
        'pass'               => env("DB_PASSWORD"),
    ],

];
