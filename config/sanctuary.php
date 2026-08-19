<?php

/**
 * DPB Sanctuary Module Configuration
 * Strictly encapsulating authentication parameters for isolation.
 */

use App\Models\User;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Guard
    |--------------------------------------------------------------------------
    | The default authentication guard utilized by the sanctuary API layer.
    |
    | Default: 'sanctuary_api'
    */
    'auth_guard' => env('DPB_SANCTUARY_AUTH_GUARD', 'sanctuary_api'),

    /*
    |--------------------------------------------------------------------------
    | Authentication Provider
    |--------------------------------------------------------------------------
    | The identifier for the authentication provider that resolves users.
    | This maps your guard to the specific user provider implementation.
    |
    | Default: 'sanctuary_ghosts'
    */
    'provider_name' => env('DPB_SANCTUARY_PROVIDER_NAME', 'sanctuary_ghosts'),

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    | The Eloquent model class representing the system users. 
    | Sanctuary uses this to resolve identity and verify credentials.
    |
    | Default: App\Models\User::class
    */
    'user_model' => env('DPB_SANCTUARY_USER_MODEL', User::class),

    /*
    |--------------------------------------------------------------------------
    | Ghost User Identification Column
    |--------------------------------------------------------------------------
    | The database column name used to identify the user during the login process
    | (e.g., 'email', 'personal_id', 'employee_number'). This column must exist 
    | on the model defined in 'user_model'.
    |
    | Default: 'email'
    */
    'ghost_user_identification_column' => env('DPB_SANCTUARY_GHOST_USER_IDENTIFIER_COLUMN', 'personal_id'),

    /*
    |--------------------------------------------------------------------------
    | Personal Access Token Name
    |--------------------------------------------------------------------------
    | The semantic string identity assigned to the generated Sanctum token.
    |
    | Default: 'sanctuary-token'
    */
    'token_name' => env('DPB_SANCTUARY_TOKEN_NAME', 'sanctuary-token'),

];