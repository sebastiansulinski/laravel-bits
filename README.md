# Laravel Bits

A set of handy utilities for any Laravel project.

## Requirements

- PHP ^8.4
- Laravel ^12.0

## Installation

You can install the package via Composer:

```bash
composer require sebastiansulinski/laravel-bits
```

The package will automatically register its service provider.

## Available Traits

### EnhancedEnums

The `EnhancedEnums` trait provides additional utility methods for PHP enums, making them more convenient to work with in
Laravel applications.

#### Usage

```php
use LaravelBits\Traits\EnhancedEnums;

enum Status: string
{
    use EnhancedEnums;

    case Active = 'active';
    case Pending = 'pending';
    case Closed = 'closed';

    public function label(): string
    {
        return ucfirst($this->value) . ' Status';
    }
}
```

#### Available Methods

- **`values(?array $cases = null): array`** - Get array of enum values
- **`names(?array $cases = null): array`** - Get array of enum names
- **`options(?array $cases = null): array`** - Get array of name/value pairs
- **`toArray(): array`** - Convert enum instance to array
- **`label(): string`** - Get label for enum case (can be overridden)
- **`except(array|self $cases): array`** - Exclude certain cases from enum
- **`collection(): Collection`** - Get cases as Laravel Collection

#### Examples

```php
// Get all values
Status::values(); // ['active', 'pending', 'closed']

// Get all names
Status::names(); // ['Active', 'Pending', 'Closed']

// Get options for dropdowns
Status::options();
// [
//     ['name' => 'Active Status', 'value' => 'active'],
//     ['name' => 'Pending Status', 'value' => 'pending'],
//     ['name' => 'Closed Status', 'value' => 'closed']
// ]

// Convert single case to array
Status::Active->toArray(); // ['name' => 'Active Status', 'value' => 'active']

// Exclude certain cases
Status::except([Status::Active, Status::Closed]); // Only Pending case

// Get as Collection
Status::collection(); // Collection of all cases
```

### Serialisable

The `Serialisable` trait provides JSON serialization capabilities for any class.

#### Usage

```php
use LaravelBits\Traits\Serialisable;

class MyClass
{
    use Serialisable;

    public function toArray(): array
    {
        return [
            'property1' => $this->property1,
            'property2' => $this->property2,
        ];
    }
}
```

#### Available Methods

- **`__toString(): string`** - Convert object to JSON string
- **`toJson($options = 0): string`** - Get JSON representation of the object
- **`toArray(): array`** - Abstract method that must be implemented

#### Examples

```php
$object = new MyClass();

// Convert to JSON string
echo $object; // Calls __toString() which returns JSON

// Get JSON with options
$json = $object->toJson(JSON_PRETTY_PRINT);
```

### Sortable

The `Sortable` trait provides utilities for handling sortable records with automatic sort position management.

#### Usage

```php
use LaravelBits\Traits\Sortable;

class MySortableService
{
    use Sortable;
    
    public function sort(array $ids): void
    {
        MyModel::upsert(
            $this->sortablePayload($ids), 'ulid', ['sort']
        );
    }

    protected function existingSortables(array $ids): Collection
    {
        return MyModel::whereIn('ulid', $ids)->get();
    }

    protected function sortableModelColumns(): array
    {
        return ['ulid', 'name', 'description'];
    }
}
```

### Sorter

Another way to handle sorting of the records is to use the `Sorter` class.

#### Available Methods

- **`sortablePayload(array $ids, string $column = 'ulid'): array`** - Generate payload for sorting records
- **`existingSortables(array $ids): Collection`** - Abstract method to get existing records
- **`sortableRange(array $ids, $startSort): array`** - Generate range of sort positions
- **`modelToArray(Model $model): array`** - Convert model to array using specific columns
- **`sortableModelColumns(): array`** - Abstract method to define which columns to include

## Available Macros

### QueryBuilder whereDateBetween

Adds a `whereDateBetween` method to Laravel's Query Builder for filtering records between two dates.

#### Usage

```php
use Illuminate\Support\Facades\DB;

// Filter records between two dates
DB::table('orders')
    ->whereDateBetween('created_at', ['2024-01-01', '2024-12-31'])
    ->get();

// With custom boolean operator
DB::table('orders')
    ->where('status', 'active')
    ->whereDateBetween('created_at', ['2024-01-01', '2024-12-31'], 'or')
    ->get();
```

#### Parameters

- **`$column`** - The column name to filter
- **`$dates`** - Array with two dates `[start_date, end_date]`
- **`$boolean`** - Boolean operator ('and' or 'or'), defaults to 'and'

### EloquentBuilder updateMany

Adds an `updateMany` method to Laravel's Eloquent Builder for efficiently updating multiple records with different
values in a single query.

#### Usage

```php
use App\Models\Book;
use LaravelBits\Data\UpdateManySet;

// Update multiple records with different sort values
Book::updateMany('id', new UpdateManySet('sort', [
    1 => 30,
    2 => 20,
    3 => 10,
]));

// Update multiple columns at once
Book::updateMany('id', [
    new UpdateManySet('sort', [1 => 30, 2 => 20, 3 => 10]),
    new UpdateManySet('priority', [1 => 'high', 2 => 'medium', 3 => 'low']),
]);

// With custom timestamp
Book::updateMany('id', new UpdateManySet('sort', [
    1 => 30,
    2 => 20,
    3 => 10,
]), now()->addHour());
```

#### Parameters

- **`$caseColumn`** - The column to match against (usually 'id')
- **`$sets`** - UpdateManySet instance or array of UpdateManySet instances
- **`$timestamp`** - Optional timestamp for updated_at (defaults to now())

#### Benefits

- **Performance**: Updates multiple records in a single SQL query using CASE statements
- **Efficiency**: Avoids N+1 query problems when updating many records
- **Automatic timestamps**: Automatically updates the `updated_at` column

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Sebastian Sulinski](https://github.com/sebastiansulinski)