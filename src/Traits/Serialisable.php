<?php

namespace LaravelBits\Traits;

trait Serialisable
{
    /**
     * Convert an object to string.
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Get JSON representation of the object.
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Get array representation of the object.
     */
    abstract public function toArray(): array;
}
