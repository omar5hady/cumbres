<?php

return [
    'Pinpoint' => [
        'region' => env('AWS_PINPOINT_REGION'),
        'application_id' => env('AWS_PINPOINT_APPLICATION_ID'),
        'sender_id' => env('AWS_PINPOINT_SENDER_ID'),
        'key' => env('AWS_PINPOINT_KEY'),
        'secret' => env('AWS_PINPOINT_SECRET'),
    ],
];