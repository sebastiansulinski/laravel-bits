<?php

namespace LaravelBits\Data;

class UpdateManySet
{
    /**
     * UpdateManySet constructor.
     */
    public function __construct(
        public string $column,
        public array $data
    ) {}
}
