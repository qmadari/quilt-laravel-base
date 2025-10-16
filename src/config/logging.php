<?php

return [
    'channels' => [
        'daily' => [
            'path' => storage_path('logs/laravel-'.posix_getpwuid(posix_geteuid())['name'].'.log'),
        ],
    ],
];
