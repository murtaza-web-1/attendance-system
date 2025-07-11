<?php

return [

    'default' => [
    'guard' => 'web', 
    'passwords' => 'users',
],
   'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',    
    ],
    'api' => [
        'driver' => 'sanctum',
        'provider' => 'users',
    ],
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
],
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],    
    
],

];
