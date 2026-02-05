<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WhereLower extends Model
{
    protected $table = 'where_lower';

    public $timestamps = false;
}

beforeEach(function () {
    Schema::create('where_lower', function (Blueprint $table) {
        $table->id();
        $table->string('email');
        $table->string('name');
    });

    DB::table('where_lower')->insert([
        ['email' => 'john@example.com', 'name' => 'John'],
        ['email' => 'jane@example.com', 'name' => 'Jane'],
        ['email' => 'admin@example.com', 'name' => 'Admin'],
    ]);
});

afterEach(function () {
    Schema::drop('where_lower');
});

it('filters by lowercased value via query builder', function () {
    $results = DB::table('where_lower')
        ->whereLower('email', 'JOHN@EXAMPLE.COM')
        ->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('John');
});

it('filters by lowercased value via eloquent builder', function () {
    $results = WhereLower::query()
        ->whereLower('email', 'JANE@EXAMPLE.COM')
        ->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('Jane');
});

it('returns no results when lowercased value does not match', function () {
    $results = DB::table('where_lower')
        ->whereLower('email', 'NONEXISTENT@EXAMPLE.COM')
        ->get();

    expect($results)->toHaveCount(0);
});

it('handles already lowercase input', function () {
    $results = WhereLower::query()
        ->whereLower('email', 'admin@example.com')
        ->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('Admin');
});

it('can be chained with other where clauses', function () {
    $results = WhereLower::query()
        ->whereLower('email', 'JOHN@EXAMPLE.COM')
        ->where('name', 'John')
        ->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->email)->toBe('john@example.com');
});

it('supports a custom operator', function () {
    $results = DB::table('where_lower')
        ->whereLower('email', '!=', 'JOHN@EXAMPLE.COM')
        ->get();

    expect($results)->toHaveCount(2)
        ->and($results->pluck('name')->all())->toBe(['Jane', 'Admin']);
});

it('supports the boolean argument', function () {
    $results = DB::table('where_lower')
        ->where('name', 'John')
        ->whereLower('email', '=', 'JANE@EXAMPLE.COM', 'or')
        ->get();

    expect($results)->toHaveCount(2)
        ->and($results->pluck('name')->all())->toBe(['John', 'Jane']);
});
