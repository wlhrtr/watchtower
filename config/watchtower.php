<?php

return [
    'url' => env('WATCHTOWER_URL'),
    'client_id' => env('WATCHTOWER_CLIENT_ID'),
    'client_secret' => env('WATCHTOWER_CLIENT_SECRET'),
    'environment' => env('WATCHTOWER_ENVIRONMENT', config('app.env')),
];
