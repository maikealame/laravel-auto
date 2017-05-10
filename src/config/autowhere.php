<?php

return [

    'db' => [
        "type" => env("DB_CONNECTION","mysql"), // only accept mysql and pgsql string
    ],
    'app_date_format' =>       "d/m/Y",     // only support "d/m/Y" or "Y-m-d"
    'db_date_format' =>        "d/m/Y"      // only support "d/m/Y" or "Y-m-d"

];
