<?php

namespace LaravelBits\Macros;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use LaravelBits\Data\UpdateManySet;

class EloquentBuilderUpdateMany
{
    /**
     * Handle request.
     */
    public function register(): void
    {
        Builder::macro('updateMany', function (
            string $caseColumn,
            array|UpdateManySet $sets,
            string|Carbon|null $timestamp = null
        ): bool {

            /** @var Builder $this */
            $timestamp ??= Carbon::now();
            $timestamp = $timestamp instanceof Carbon
                ? $timestamp
                : Carbon::parse($timestamp);

            $updates = [];
            $cases = [];
            $bindings = [];
            $ids = [];

            $sets = is_array($sets) ? $sets : [$sets];

            /** @var UpdateManySet $set */
            foreach ($sets as $set) {
                foreach ($set->data as $id => $value) {
                    $cases[$set->column][] = 'WHEN ? THEN ?';
                    $bindings[] = $id;
                    $bindings[] = $value;
                    $ids[] = $id;
                }
                $updates[] = vsprintf('%s = CASE %s %s END', [
                    $set->column,
                    $caseColumn,
                    implode(' ', $cases[$set->column]),
                ]);
            }

            $ids = array_unique($ids);

            return $this->getConnection()->update(vsprintf('
                UPDATE %s 
                SET %s, `updated_at` = ?
                WHERE %s IN (%s)',
                [
                    $this->getModel()->getTable(),
                    implode(',', $updates),
                    $caseColumn,
                    implode(',', array_fill(1, count($ids), '?')),
                ]
            ), array_merge($bindings, [$timestamp], $ids));
        });
    }
}
