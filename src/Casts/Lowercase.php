<?php

namespace LaravelBits\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<string, string>
 */
class Lowercase implements CastsAttributes
{
    /**
     * Cast the given value.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value !== null ? mb_strtolower($value) : null;
    }

    /**
     * Prepare the given value for storage.
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value !== null ? mb_strtolower($value) : null;
    }
}
