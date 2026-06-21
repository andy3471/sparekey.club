<?php

declare(strict_types=1);

return [

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],

        'public' => [
            'driver'                  => 's3',
            'key'                     => env('AWS_ACCESS_KEY_ID'),
            'secret'                  => env('AWS_SECRET_ACCESS_KEY'),
            'region'                  => env('AWS_DEFAULT_REGION', 'auto'),
            'bucket'                  => env('AWS_BUCKET'),
            'url'                     => env('AWS_URL'),
            'endpoint'                => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'cdn_endpoint'            => env('AWS_CDN_ENDPOINT'),
            'visibility'              => 'public',
            'throw'                   => false,
        ],

        'private' => [
            'driver'                  => 's3',
            'key'                     => env('AWS_PRIVATE_ACCESS_KEY_ID', env('AWS_ACCESS_KEY_ID')),
            'secret'                  => env('AWS_PRIVATE_SECRET_ACCESS_KEY', env('AWS_SECRET_ACCESS_KEY')),
            'region'                  => env('AWS_PRIVATE_DEFAULT_REGION', env('AWS_DEFAULT_REGION', 'auto')),
            'bucket'                  => env('AWS_PRIVATE_BUCKET'),
            'url'                     => env('AWS_PRIVATE_URL'),
            'endpoint'                => env('AWS_PRIVATE_ENDPOINT', env('AWS_ENDPOINT')),
            'use_path_style_endpoint' => env('AWS_PRIVATE_USE_PATH_STYLE_ENDPOINT', env('AWS_USE_PATH_STYLE_ENDPOINT', false)),
            'throw'                   => false,
        ],
    ],

];
