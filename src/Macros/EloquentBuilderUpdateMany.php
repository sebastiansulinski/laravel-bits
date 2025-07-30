<?php

namespace LaravelBits\Macros;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EloquentBuilderUpdateMany
{
    /**
     * Handle request.
     */
    public function register(): void
    {
        Builder::macro('updateMany', function (string $caseColumn, string $setColumn, array $data, string|Carbon|null $timestamp = null) {

            /** @var Builder $this */
            $timestamp ??= Carbon::now();
            $timestamp = $timestamp instanceof Carbon
                ? $timestamp
                : Carbon::parse($timestamp);

            $cases = [];
            $bindings = [];
            $ids = [];

            foreach ($data as $id => $value) {
                $cases[] = 'WHEN ? THEN ?';
                $bindings[] = $id;
                $bindings[] = $value;
                $ids[] = $id;
            }

            return DB::update(vsprintf('
                UPDATE %s 
                SET %s = CASE %s %s END, 
                    `updated_at` = ?
                WHERE %s IN (%s)',
                [
                    $this->getModel()->getTable(),
                    $setColumn,
                    $caseColumn,
                    implode(' ', $cases),
                    $caseColumn,
                    implode(',', array_fill(1, count($ids), '?')),
                ]
            ), array_merge($bindings, [$timestamp], $ids));
        });
    }
}
