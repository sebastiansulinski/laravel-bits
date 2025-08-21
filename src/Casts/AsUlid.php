<?php

namespace LaravelBits\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Uid\Ulid;

/**
 * @implements CastsAttributes<Ulid|null, mixed>
 */
readonly class AsUlid implements CastsAttributes
{
    /**
     * {@inheritDoc}
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Ulid
    {
        return $value ? new Ulid(is_string($value) ? $value : null) : null;
    }

    /**
     * {@inheritDoc}
     */
    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        return $value;
    }
}
