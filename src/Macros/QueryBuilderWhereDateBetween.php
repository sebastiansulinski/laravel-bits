<?php

namespace LaravelBits\Macros;

use Illuminate\Database\Query\Builder;

class QueryBuilderWhereDateBetween
{
    /**
     * Handle request.
     */
    public function register(): void
    {
        Builder::macro('whereDateBetween',
            function (string $column, array $dates, string $boolean = 'and') {
                [$start, $end] = $dates;

                /** @var Builder $this */

                return $this->where(
                    column: fn (Builder $builder) => $builder
                        ->whereDate($column, '>=', $start)
                        ->whereDate($column, '<=', $end),
                    boolean: $boolean
                );
            });
    }
}
