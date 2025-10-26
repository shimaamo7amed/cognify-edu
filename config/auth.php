<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'employee' => [ // new
            'driver' => 'session',
            'provider' => 'employees',
        ],

        'parent' => [
            'driver' => 'session',
            'provider' => 'parents',
        ],
    ],


    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'employees' => [
            'driver' => 'eloquent',
            'model' => App\Models\Employee::class,
        ],

        'parents' => [
            'driver' => 'eloquent',
            'model' => App\Models\CognifyParent::class,
        ],
    ],

];