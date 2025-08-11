<?php

namespace LaravelBits\Macros;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ArrKeysToCamel
{
    /**
     * Handle request.
     */
    public function register(): void
    {
        Arr::macro('keysToCamel', function (array $array): array {
            $output = [];

            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $value = Arr::keysToCamel($value);
                }

                $output[Str::camel($key)] = $value;
            }

            return $output;
        });

    }
}
