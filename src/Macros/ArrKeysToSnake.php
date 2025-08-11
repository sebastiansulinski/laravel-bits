<?php

namespace LaravelBits\Macros;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ArrKeysToSnake
{
    /**
     * Handle request.
     */
    public function register(): void
    {
        Arr::macro('keysToSnake', function (array $array): array {
            $output = [];

            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $value = Arr::keysToSnake($value);
                }

                $output[Str::snake($key)] = $value;
            }

            return $output;
        });
    }
}
