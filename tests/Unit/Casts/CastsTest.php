<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use LaravelBits\Casts\AsBoolean;
use LaravelBits\Casts\AsUlid;
use LaravelBits\Casts\Lowercase;
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

it('lowercases value when getting', function () {

    $model = new class extends Model
    {
        protected $fillable = ['email'];

        protected function casts(): array
        {
            return [
                'email' => Lowercase::class,
            ];
        }
    };

    $instance = new $model;
    $instance->setRawAttributes(['email' => 'JOHN@EXAMPLE.COM']);

    expect($instance->email)->toBe('john@example.com');
});

it('lowercases value when setting', function () {

    $model = new class extends Model
    {
        protected $fillable = ['email'];

        protected function casts(): array
        {
            return [
                'email' => Lowercase::class,
            ];
        }
    };

    $instance = new $model(['email' => 'JOHN@EXAMPLE.COM']);

    expect($instance->getAttributes()['email'])->toBe('john@example.com');
});

it('handles null value in lowercase cast', function () {

    $model = new class extends Model
    {
        protected $fillable = ['email'];

        protected function casts(): array
        {
            return [
                'email' => Lowercase::class,
            ];
        }
    };

    $instance = new $model;
    $instance->setRawAttributes(['email' => null]);

    expect($instance->email)->toBeNull();

    $instance2 = new $model(['email' => null]);

    expect($instance2->getAttributes()['email'])->toBeNull();
});

it('handles already lowercase value in lowercase cast', function () {

    $model = new class extends Model
    {
        protected $fillable = ['email'];

        protected function casts(): array
        {
            return [
                'email' => Lowercase::class,
            ];
        }
    };

    $instance = new $model(['email' => 'john@example.com']);

    expect($instance->getAttributes()['email'])->toBe('john@example.com');

    $instance->setRawAttributes(['email' => 'john@example.com']);

    expect($instance->email)->toBe('john@example.com');
});

it('lowercases mixed case value in lowercase cast', function () {

    $model = new class extends Model
    {
        protected $fillable = ['email'];

        protected function casts(): array
        {
            return [
                'email' => Lowercase::class,
            ];
        }
    };

    $instance = new $model(['email' => 'John.Doe@Example.COM']);

    expect($instance->getAttributes()['email'])->toBe('john.doe@example.com');

    $instance->setRawAttributes(['email' => 'John.Doe@Example.COM']);

    expect($instance->email)->toBe('john.doe@example.com');
});

it('lowercases unicode characters in lowercase cast', function () {

    $model = new class extends Model
    {
        protected $fillable = ['name'];

        protected function casts(): array
        {
            return [
                'name' => Lowercase::class,
            ];
        }
    };

    $instance = new $model(['name' => 'ÜBERMÜTIG']);

    expect($instance->getAttributes()['name'])->toBe('übermütig');

    $instance->setRawAttributes(['name' => 'ÉLÉPHANT']);

    expect($instance->name)->toBe('éléphant');
});

it('lowercases mixed ascii and unicode characters in lowercase cast', function () {

    $model = new class extends Model
    {
        protected $fillable = ['name'];

        protected function casts(): array
        {
            return [
                'name' => Lowercase::class,
            ];
        }
    };

    $instance = new $model(['name' => 'José GARCÍA']);

    expect($instance->getAttributes()['name'])->toBe('josé garcía');

    $instance->setRawAttributes(['name' => 'MÜNCHEN City']);

    expect($instance->name)->toBe('münchen city');
});
