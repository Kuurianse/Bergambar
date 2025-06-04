<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'], // Added login/logout for SPA auth

    'allowed_methods' => ['*'],

    // For development, be specific. For production, use your actual frontend domain.
    // Consider moving this to your .env file: ALLOWED_ORIGINS=http://localhost:3000,https://your-admin-domain.com
    'allowed_origins' => explode(',', env('ALLOWED_ORIGINS', 'http://localhost:3000')),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Or be more specific: ['Content-Type', 'X-Requested-With', 'Accept', 'Authorization', 'X-XSRF-TOKEN']

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Crucial for Sanctum SPA authentication

];
