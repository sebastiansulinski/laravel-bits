<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WhereLowercase extends Model
{
    protected $table = 'where_lowercase';

    public $timestamps = false;
}

beforeEach(function () {
    Schema::create('where_lowercase', function (Blueprint $table) {
        $table->id();
        $table->string('email');
        $table->string('name');
    });

    DB::table('where_lowercase')->insert([
        ['email' => 'john@example.com', 'name' => 'John'],
        ['email' => 'jane@example.com', 'name' => 'Jane'],
        ['email' => 'admin@example.com', 'name' => 'Admin'],
    ]);
});

afterEach(function () {
    Schema::drop('where_lowercase');
});

it('filters by lowercased value via query builder', function () {
    $results = DB::table('where_lowercase')
        ->whereLowercase('email', 'JOHN@EXAMPLE.COM')
        ->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('John');
});

it('filters by lowercased value via eloquent builder', function () {
    $results = WhereLowercase::query()
        ->whereLowercase('email', 'JANE@EXAMPLE.COM')
        ->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('Jane');
});

it('returns no results when lowercased value does not match', function () {
    $results = DB::table('where_lowercase')
        ->whereLowercase('email', 'NONEXISTENT@EXAMPLE.COM')
        ->get();

    expect($results)->toHaveCount(0);
});

it('handles already lowercase input', function () {
    $results = WhereLowercase::query()
        ->whereLowercase('email', 'admin@example.com')
        ->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('Admin');
});

it('can be chained with other where clauses', function () {
    $results = WhereLowercase::query()
        ->whereLowercase('email', 'JOHN@EXAMPLE.COM')
        ->where('name', 'John')
        ->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->email)->toBe('john@example.com');
});

it('supports a custom operator', function () {
    $results = DB::table('where_lowercase')
        ->whereLowercase('email', '!=', 'JOHN@EXAMPLE.COM')
        ->get();

    expect($results)->toHaveCount(2)
        ->and($results->pluck('name')->all())->toBe(['Jane', 'Admin']);
});

it('supports the boolean argument', function () {
    $results = DB::table('where_lowercase')
        ->where('name', 'John')
        ->whereLowercase('email', '=', 'JANE@EXAMPLE.COM', 'or')
        ->get();

    expect($results)->toHaveCount(2)
        ->and($results->pluck('name')->all())->toBe(['John', 'Jane']);
});

it('lowercases unicode characters', function () {
    DB::table('where_lowercase')->insert([
        ['email' => 'müller@example.com', 'name' => 'Müller'],
    ]);

    $results = DB::table('where_lowercase')
        ->whereLowercase('email', 'MÜLLER@EXAMPLE.COM')
        ->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('Müller');
});

it('lowercases mixed ascii and unicode characters', function () {
    DB::table('where_lowercase')->insert([
        ['email' => 'josé@example.com', 'name' => 'José'],
    ]);

    $results = WhereLowercase::query()
        ->whereLowercase('email', 'JOSÉ@EXAMPLE.COM')
        ->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('José');
});
