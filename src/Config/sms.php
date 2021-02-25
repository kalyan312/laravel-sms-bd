<?php

return [


    /*
    |--------------------------------------------------------------------------
    | Record SMS Log
    |--------------------------------------------------------------------------
    |
    | This value determines that should take sms record in db or not
    | You can switch to a different gateway at runtime.
    | set value true to Record Log
    |
    */

    'sms_log' => env('SMS_LOG', false),

    /*
    |--------------------------------------------------------------------------
    | Default Gateway
    |--------------------------------------------------------------------------
    |
    | This value determines which of the following gateway to use.
    | You can switch to a different gateway at runtime.
    |
    */

    'default' => env('DEFAULT_SMS_GATEWAY', 'bangladesh_sms'),

    /*
    |--------------------------------------------------------------------------
    | List of Gateways
    |--------------------------------------------------------------------------
    |
    | These are the list of gateways to use for this package.
    | You can change the name. Then you'll have to change
    | it in the map array too.
    |
    */

    'gateways' => [

        'bangladesh_sms' => [
            'base_url' => env('BANGLADESH_SMS_BASE_URL'),
            'username' => env('BANGLADESH_SMS_USERNAME'),
            'api_key'  => env('BANGLADESH_SMS_API_KEY'),
            'from'     => env('BANGLADESH_SMS_FROM'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Maps
    |--------------------------------------------------------------------------
    |
    | This is the array of Classes that maps to Gateways above.
    | You can create your own driver if you like and add the
    | config in the drivers array and the class to use
    | here with the same name. You will have to implement
    | Khbd\LaravelSmsBD\Contracts\SMSContract in your gateway.
    |
    */

    'map' => [
        'bangladesh_sms' => \Khbd\LaravelSmsBD\Gateways\BangladeshSMS::class

    ],
];
