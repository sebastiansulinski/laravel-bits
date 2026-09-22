<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Strict Eloquent Models
    |--------------------------------------------------------------------------
    |
    | Whether Eloquent should run in strict mode, which prevents lazy loading,
    | silently discarding attributes and accessing missing attributes.
    |
    | Left unset, the package decides at runtime exactly as it always has:
    | strict in every environment the application does not report as
    | "production". That reading follows the container, so Artisan's --env
    | flag moves it, which reading the APP_ENV variable here would not.
    |
    | Set it to override that decision. Applications with a production
    | equivalent environment under a different name - user acceptance testing,
    | for instance - should do so, otherwise that environment ends up stricter
    | than production itself.
    |
    | The service provider casts an explicit value with a plain boolean cast
    | rather than filter_var, so anything not already recognised as false ends
    | up strict. Strict is the noisy direction, which is the safe one to fall
    | towards when the value is malformed.
    |
    */

    'strict_models' => env('LARAVEL_BITS_STRICT_MODELS', null),

];
