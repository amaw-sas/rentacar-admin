<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GoHighLevel API Configuration (Multi-Franchise)
    |--------------------------------------------------------------------------
    |
    | Each franchise has its own GHL sub-account with separate credentials.
    | The franchise key is derived from Str::slug(franchise.name, '').
    |
    */

    'franchises' => [
        'alquilatucarro' => [
            'api_key' => env('ALQUILATUCARRO_GHL_API_KEY'),
            'location_id' => env('ALQUILATUCARRO_GHL_LOCATION_ID'),
            'pipeline_id' => env('ALQUILATUCARRO_GHL_PIPELINE_ID'),
            'webhook_secret' => env('ALQUILATUCARRO_GHL_WEBHOOK_SECRET'),
            'stages' => [
                'pendiente' => env('ALQUILATUCARRO_GHL_STAGE_PENDIENTE'),
                'reservado' => env('ALQUILATUCARRO_GHL_STAGE_RESERVADO'),
                'pendiente_modificar' => env('ALQUILATUCARRO_GHL_STAGE_PENDIENTE_MODIFICAR'),
                'utilizado' => env('ALQUILATUCARRO_GHL_STAGE_UTILIZADO'),
                'sin_disponibilidad' => env('ALQUILATUCARRO_GHL_STAGE_SIN_DISPONIBILIDAD'),
                'mensualidad' => env('ALQUILATUCARRO_GHL_STAGE_MENSUALIDAD'),
            ],
        ],

        'alquilame' => [
            'api_key' => env('ALQUILAME_GHL_API_KEY'),
            'location_id' => env('ALQUILAME_GHL_LOCATION_ID'),
            'pipeline_id' => env('ALQUILAME_GHL_PIPELINE_ID'),
            'webhook_secret' => env('ALQUILAME_GHL_WEBHOOK_SECRET'),
            'stages' => [
                'cotizado' => env('ALQUILAME_GHL_STAGE_COTIZADO'),
                'pendiente' => env('ALQUILAME_GHL_STAGE_PENDIENTE'),
                'reservado' => env('ALQUILAME_GHL_STAGE_RESERVADO'),
                'pendiente_modificar' => env('ALQUILAME_GHL_STAGE_PENDIENTE_MODIFICAR'),
                'utilizado' => env('ALQUILAME_GHL_STAGE_UTILIZADO'),
                'sin_disponibilidad' => env('ALQUILAME_GHL_STAGE_SIN_DISPONIBILIDAD'),
                'mensualidad' => env('ALQUILAME_GHL_STAGE_MENSUALIDAD'),
            ],
        ],

        'alquicarros' => [
            'api_key' => env('ALQUICARROS_GHL_API_KEY'),
            'location_id' => env('ALQUICARROS_GHL_LOCATION_ID'),
            'pipeline_id' => env('ALQUICARROS_GHL_PIPELINE_ID'),
            'webhook_secret' => env('ALQUICARROS_GHL_WEBHOOK_SECRET'),
            'stages' => [
                'pendiente' => env('ALQUICARROS_GHL_STAGE_PENDIENTE'),
                'reservado' => env('ALQUICARROS_GHL_STAGE_RESERVADO'),
                'pendiente_modificar' => env('ALQUICARROS_GHL_STAGE_PENDIENTE_MODIFICAR'),
                'utilizado' => env('ALQUICARROS_GHL_STAGE_UTILIZADO'),
                'sin_disponibilidad' => env('ALQUICARROS_GHL_STAGE_SIN_DISPONIBILIDAD'),
                'mensualidad' => env('ALQUICARROS_GHL_STAGE_MENSUALIDAD'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | GHL API Settings
    |--------------------------------------------------------------------------
    */

    'api_base_url' => env('GHL_API_BASE_URL', 'https://services.leadconnectorhq.com'),
    'api_version' => env('GHL_API_VERSION', '2021-07-28'),
    'timeout' => env('GHL_TIMEOUT', 30),
];
