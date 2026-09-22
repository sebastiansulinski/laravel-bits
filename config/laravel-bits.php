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
    | The default reproduces the behaviour this package has always had: strict
    | in every environment except "production". Applications with a production
    | equivalent environment under a different name - user acceptance testing,
    | for instance - should set this explicitly so that it does not end up
    | stricter than production itself.
    |
    | The value is deliberately cast with a plain boolean cast rather than
    | filter_var, so that anything Laravel does not already recognise as false
    | ends up strict. Strict is the noisy direction, which is the safe one to
    | fall towards when the value is malformed.
    |
    */

    'strict_models' => (bool) env('LARAVEL_BITS_STRICT_MODELS', env('APP_ENV', 'production') !== 'production'),

];
