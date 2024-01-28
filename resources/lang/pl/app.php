<?php

use Illuminate\Support\Facades\Config;

return [
    'pages' => [
        'panel' => [
            'title' => Config::get('app.name'),
            'description' => '',
            'keywords' => ''
        ]
    ],
];
