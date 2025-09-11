<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    |
    | Pengaturan ini mengontrol Cross-Origin Resource Sharing (CORS).
    | Kalau frontend beda domain (misalnya localhost:5173 untuk Vite),
    | kamu perlu aktifkan origin-nya di sini.
    |
    */

    'paths' => ['api/*', 'admin/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173', // untuk development Vite
        'https://koperasi-kosgoro-app-production.up.railway.app', // domain Railway
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];
