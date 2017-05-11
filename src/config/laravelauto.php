<?php

return [

    // where functions

    'db' => [
        /*
        DB type for correct query syntax
        note: only accept mysql and pgsql string
        */
        "type" => env("DB_CONNECTION","mysql"),
    ],
    /*
    date format used for this App
    note: only support "d/m/Y" or "Y-m-d"
    */
    'app_date_format' =>       "d/m/Y",
    /*
    date format used for DB
    note: only support "d/m/Y" or "Y-m-d"
    */
    'db_date_format' =>        "d/m/Y",      // only support "d/m/Y" or "Y-m-d"



    // pages function

    'pages' => [
        /*
        combobox <select> options to divide pagination
        */
        'length' => array(5,10,20,30,50,100),
        /*
        default length os combobox and query if not set HTTP GET Parameter
        */
        'default_length' => 10
    ],

    // sort function

    'sort' => [
        /*
        spec columns
        */
        'columns' => [
            'alpha'    => [
                'rows' => ['description', 'email', 'name', 'slug'],
                'class' => 'fa fa-sort-alpha',
            ],
            'amount'   => [
                'rows' => ['amount', 'price'],
                'class' => 'fa fa-sort-amount'
            ],
            'numeric'  => [
                'rows' => ['created_at', 'updated_at', 'level', 'id', 'phone_number'],
                'class' => 'fa fa-sort-numeric'
            ],
        ],
        /*
        defines icon set to use when sorted data is none above (alpha nor amount nor numeric)
         */
        'default_icon_set' => 'fa fa-sort',
        /*
        icon that shows when generating sortable link while column is not sorted
         */
        'sortable_icon'    => 'fa fa-sort',
        /*
        generated icon is clickable non-clickable (default)
         */
        'clickable_icon' => false,
        /*
        icon and text separator (any string)
        in case of 'clickable_icon' => true; separator creates possibility to style icon and anchor-text properly
         */
        'icon_text_separator' => ' ',
        /*
        suffix class that is appended when ascending order is applied
         */
        'asc_suffix'        => '-asc',
        /*
        suffix class that is appended when descending order is applied
         */
        'desc_suffix'       => '-desc',
        /*
        default anchor class, if value is null none is added
         */
        'anchor_class'      => null,
        /*
        relation - column separator ex: detail.phone_number means relation "detail" and column "phone_number"
         */
        'uri_relation_column_separator' => '.',
        /*
        formatting function applied to name of column, use null to turn formatting off
         */
        'formatting_function' => 'ucfirst',
        /*
        allow request modification, when default sorting is set but is not in URI (first load)
         */
        'allow_request_modification'  =>  true,
        /*
        default order for: $user->sortable(['id']) usage
         */
        'default_order' => 'asc',
        /*
        default order for non-sorted columns
         */
        'default_order_unsorted' => 'asc'
    ]
];
