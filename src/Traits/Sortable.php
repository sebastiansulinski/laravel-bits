<?php

namespace LaravelBits\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Sortable
{
    /**
     * Get sortable payload.
     */
    protected function sortablePayload(array $ids, string $column = 'ulid'): array
    {
        $existing = $this->existingSortables($ids);

        $range = $this->sortableRange($ids, $existing->min('sort'));

        $output = [];

        foreach ($ids as $index => $id) {
            $output[$index] = array_merge(
                [$column => $id],
                $this->modelToArray($existing->firstWhere($column, $id)),
                ['sort' => $range[$index]]
            );
        }

        return $output;
    }

    /**
     * Get existing records.
     *
     * @return Collection<Model>
     */
    abstract protected function existingSortables(array $ids): Collection;

    /**
     * Get a sortable range.
     */
    protected function sortableRange(array $ids, $startSort): array
    {
        return range($startSort, $startSort + (count($ids) - 1));
    }

    /**
     * Convert model to array.
     */
    protected function modelToArray(Model $model): array
    {
        return $model->only($this->sortableModelColumns());
    }

    /**
     * Get sortable model columns.
     */
    abstract protected function sortableModelColumns(): array;
}
