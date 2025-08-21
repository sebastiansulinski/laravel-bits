<?php

namespace LaravelBits\Utilities\Sorter;

use Closure;
use Illuminate\Support\Collection;

class SorterPayload
{
    public Collection $records;

    /**
     * SorterPayload constructor.
     *
     * @param  Collection<\Illuminate\Database\Eloquent\Model>  $models
     * @param  array<int, mixed>  $ids
     */
    public function __construct(Collection $models, array $ids, Closure|string $filter = 'id')
    {
        $this->records = new Collection(array_map(
            fn (mixed $id) => $models->filter(
                fn ($model) => is_string($filter)
                    ? $model->{$filter} === $id
                    : $filter($model, $id)
            )->first(),
            $ids
        ));
    }
}
