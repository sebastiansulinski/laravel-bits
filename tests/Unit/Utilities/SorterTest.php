<?php

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use LaravelBits\Casts\AsUlid;
use LaravelBits\Data\UpdateManySet;
use LaravelBits\Utilities\Sorter\Sorter;
use LaravelBits\Utilities\Sorter\SorterPayload;

uses(RefreshDatabase::class);

class SorterModel extends Model
{
    use HasFactory;

    public static $factory = SorterModelFactory::class;

    protected $table = 'sorter_models';

    protected $fillable = [
        'ulid',
        'name',
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'ulid' => AsUlid::class,
        ];
    }
}

class SorterModelFactory extends Factory
{
    protected $model = SorterModel::class;

    public function definition(): array
    {
        static $sort = 1;

        return [
            'ulid' => Str::ulid(),
            'name' => $this->faker->name(),
            'sort' => $sort++,
        ];
    }
}

beforeEach(function () {
    Schema::create('sorter_models', function ($table) {
        $table->id();
        $table->ulid('ulid')->unique();
        $table->string('name');
        $table->integer('sort');
        $table->timestamps();
    });
});

afterEach(function () {
    Schema::drop('sorter_models');
});

it('generates correct set without the callback', function () {

    $records = SorterModel::factory()
        ->count(20)
        ->sequence(fn (Sequence $sequence) => [
            'sort' => $sequence->index + 1,
        ])
        ->create();

    $ulIds = [
        $records->firstWhere('sort', 16)->ulid->toString(),
        $records->firstWhere('sort', 18)->ulid->toString(),
        $records->firstWhere('sort', 17)->ulid->toString(),
        $records->firstWhere('sort', 19)->ulid->toString(),
        $records->firstWhere('sort', 20)->ulid->toString(),
    ];

    $sorterPayload = new SorterPayload(
        models: $records->where('sort', '>', 15),
        ids: $ulIds,
        filter: fn (SorterModel $model, string $ulid) => $model->ulid->toString() === $ulid
    );

    $sorter = new Sorter(
        payload: $sorterPayload,
    );

    expect($sorter->getSet())
        ->toBeInstanceOf(UpdateManySet::class)
        ->and($sorter->getSet()->column)
        ->toBe('sort')
        ->and($sorter->getSet()->data)
        ->toBe([
            16 => 16,
            18 => 17,
            17 => 18,
            19 => 19,
            20 => 20,
        ]);
});

it('generates correct set with the callback', function () {

    $records = SorterModel::factory()
        ->count(20)
        ->sequence(fn (Sequence $sequence) => [
            'name' => 'Name '.$sequence->index,
            'sort' => $sequence->index + 1,
        ])
        ->create();

    $ulIds = [
        $records->firstWhere('sort', 16)->ulid->toString(),
        $records->firstWhere('sort', 18)->ulid->toString(),
        $records->firstWhere('sort', 17)->ulid->toString(),
        $records->firstWhere('sort', 19)->ulid->toString(),
        $records->firstWhere('sort', 20)->ulid->toString(),
    ];

    $sorterPayload = new SorterPayload(
        models: $records->where('sort', '>', 15),
        ids: $ulIds,
        filter: fn (SorterModel $model, string $ulid) => $model->ulid->toString() === $ulid
    );

    $sorter = new Sorter(
        payload: $sorterPayload,
        sortColumn: 'sort',
        updateColumn: 'name',
    );

    $set = $sorter->getSet(
        fn (SorterModel $model, int $index, int $sort) => vsprintf('Name %d, %d', [
            $index, $sort,
        ]));

    expect($set)
        ->toBeInstanceOf(UpdateManySet::class)
        ->and($set->column)
        ->toBe('name')
        ->and($set->data)
        ->toBe([
            16 => 'Name 0, 16',
            18 => 'Name 1, 17',
            17 => 'Name 2, 18',
            19 => 'Name 3, 19',
            20 => 'Name 4, 20',
        ]);
});
