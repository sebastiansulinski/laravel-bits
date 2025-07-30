<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use LaravelBits\Traits\Sortable;

beforeEach(function () {
    Schema::create('films', function ($table) {
        $table->id();
        $table->string('title');
        $table->integer('sort');
        $table->timestamps();
    });
});

class Film extends Model
{
    public $timestamps = false;

    protected $table = 'films';

    protected $fillable = [
        'title',
        'sort',
    ];
}

class SortFilms
{
    use Sortable;

    public array $payload = [];

    public function handle(array $items): void
    {
        $this->payload = $this->sortablePayload(array_column($items, 'id'), 'id');
    }

    protected function existingSortables(array $ids): Collection
    {
        return Film::whereIn('id', $ids)->get();
    }

    protected function sortableModelColumns(): array
    {
        return ['id', 'title', 'sort'];
    }
}

it('correctly generates sortable payload', function () {

    Film::insert([
        [
            'id' => 1,
            'title' => 'The Hobbit',
            'sort' => 1,
        ],
        [
            'id' => 2,
            'title' => 'The Eternal Sunshine of the Spotless Mind',
            'sort' => 2,
        ],
    ]);

    $this->assertDatabaseCount(Film::class, 2);

    $sortBooks = new SortFilms;

    $sortBooks->handle([
        ['id' => 2], ['id' => 1],
    ]);

    $this->assertEquals([
        [
            'id' => 2,
            'title' => 'The Eternal Sunshine of the Spotless Mind',
            'sort' => 1,
        ],
        [
            'id' => 1,
            'title' => 'The Hobbit',
            'sort' => 2,
        ],
    ], $sortBooks->payload);
});
