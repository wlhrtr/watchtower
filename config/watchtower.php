<?php

return [
    'url' => env('WATCHTOWER_URL', null),
    'client_id' => env('WATCHTOWER_CLIENT_ID', null),
    'client_secret' => env('WATCHTOWER_CLIENT_SECRET', null),
    'environment' => env('WATCHTOWER_ENVIRONMENT', config('app.env')),
];
