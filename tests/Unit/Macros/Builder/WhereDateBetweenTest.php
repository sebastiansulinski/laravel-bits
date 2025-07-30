<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WhereDateBetween extends Model
{
    protected $table = 'where_date_between';

    /**
     * {@inheritDoc}
     */
    public function casts(): array
    {
        return [
            'date' => Carbon::class,
        ];
    }
}

beforeEach(function () {
    Schema::create('where_date_between', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->dateTime('date');
    });
});

afterEach(function () {
    Schema::drop('where_date_between');
});

it('correctly applies where date between clause', function () {

    DB::table('where_date_between')
        ->insert([
            ['name' => fake()->word, 'date' => now()->subDays(10)],
            ['name' => fake()->word, 'date' => now()->subDays(9)],
            ['name' => fake()->word, 'date' => now()->subDays(8)],
            ['name' => fake()->word, 'date' => now()->subDays(8)],
            ['name' => fake()->word, 'date' => now()->subDays(6)],
            ['name' => fake()->word, 'date' => now()->subDays(6)],
            ['name' => fake()->word, 'date' => now()->subDays(6)],
            ['name' => fake()->word, 'date' => now()],
        ]);

    $this->assertCount(6, WhereDateBetween::query()->whereDateBetween('date', [
        now()->subDays(8), now(),
    ])->get());

    $this->assertCount(6, DB::table('where_date_between')->whereDateBetween('date', [
        now()->subDays(8), now(),
    ])->get());
});
