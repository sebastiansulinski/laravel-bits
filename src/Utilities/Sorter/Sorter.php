<?php

namespace LaravelBits\Utilities\Sorter;

use Closure;
use Illuminate\Database\Eloquent\Model;
use LaravelBits\Data\UpdateManySet;

class Sorter
{
    /**
     * Sorter constructor.
     */
    public function __construct(
        public SorterPayload $payload,
        public string $idColumn = 'id',
        public string $sortColumn = 'sort',
        public string $updateColumn = 'sort',
    ) {}

    /**
     * Get the update set for sorting.
     */
    public function getSet(?Closure $callback = null): UpdateManySet
    {
        $range = $this->sortableRange($this->payload->records->min($this->sortColumn));

        $sortUpdate = $this->payload->records->mapWithKeys(
            fn (Model $model, int $index) => [
                $model[$this->idColumn] => $callback
                    ? $callback($model, $index, $range[$index])
                    : $range[$index],
            ]
        )->toArray();

        return new UpdateManySet($this->updateColumn, $sortUpdate);
    }

    /**
     * Get a sortable range.
     */
    protected function sortableRange($startSort): array
    {
        return range($startSort, $startSort + ($this->payload->records->count() - 1));
    }
}
