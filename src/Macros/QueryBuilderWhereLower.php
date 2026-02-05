<?php

namespace LaravelBits\Macros;

use Illuminate\Database\Query\Builder;

class QueryBuilderWhereLower
{
    /**
     * Handle request.
     */
    public function register(): void
    {
        Builder::macro('whereLower',
            function (string $column, mixed $operator = null, mixed $value = null, string $boolean = 'and') {
                /** @var Builder $this */
                [$value, $operator] = $this->prepareValueAndOperator(
                    $value, $operator, func_num_args() === 2
                );

                return $this->where($column, $operator, strtolower($value), $boolean);
            });
    }
}
