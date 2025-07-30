<?php

namespace LaravelBits\Traits;

use Illuminate\Support\Collection;

trait EnhancedEnums
{
    /**
     * Return the values.
     */
    public static function values(?array $cases = null): array
    {
        return array_column($cases ?? self::cases(), 'value');
    }

    /**
     * Return the names.
     */
    public static function names(?array $cases = null): array
    {
        return array_column($cases ?? self::cases(), 'name');
    }

    /**
     * Get array of options.
     */
    public static function options(?array $cases = null): array
    {
        return array_values(array_map(
            fn (self $enum) => $enum->toArray(),
            $cases ?? self::cases()
        ));
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->label(),
            'value' => $this->value,
        ];
    }

    /**
     * Get a label for the enum case.
     */
    public function label(): string
    {
        return $this->name;
    }

    /**
     * Exclude certain cases.
     */
    public static function except(array|self $cases): array
    {
        $cases = is_array($cases) ? $cases : [$cases];

        return array_filter(
            self::cases(),
            fn (self $enum) => ! in_array($enum, $cases)
        );
    }

    /**
     * Get cases in the Collection format.
     */
    public static function collection(): Collection
    {
        return new Collection(self::cases());
    }
}
