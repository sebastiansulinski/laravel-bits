<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use LaravelBits\Casts\AsBoolean;
use LaravelBits\Casts\AsUlid;
use Symfony\Component\Uid\Ulid;

uses(RefreshDatabase::class);

it('correctly casts string to ulid and merges fillable and casts via trait', function () {

    $model = new class extends Model
    {
        protected $fillable = ['ulid', 'name'];

        protected function casts(): array
        {
            return [
                'ulid' => AsUlid::class,
            ];
        }
    };

    expect(new $model(['ulid' => $ulid = Str::ulid()->toString()])->ulid)
        ->toBeInstanceOf(Ulid::class)
        ->toEqual(new Ulid($ulid));
});

it('correctly casts to boolean', function () {

    $model = new class extends Model
    {
        protected $fillable = ['id', 'name', 'is_active'];

        protected $casts = [
            'is_active' => AsBoolean::class,
        ];
    };

    expect(new $model(['is_active' => null])->is_active)
        ->toBe(false)
        ->and(new $model(['is_active' => 1])->is_active)
        ->toBe(true)
        ->and(new $model(['is_active' => ''])->is_active)
        ->toBe(false);
});
