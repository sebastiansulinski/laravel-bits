<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('books', function ($table) {
        $table->id();
        $table->string('title');
        $table->integer('sort');
        $table->timestamps();
    });
});

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'title',
        'sort',
    ];
}

it('can update many records with single query', function () {

    Book::insert([
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
        [
            'id' => 3,
            'title' => 'Mary Poppins',
            'sort' => 3,
        ],
    ]);

    $this->assertDatabaseCount(Book::class, 3);

    Book::updateMany('id', 'sort', [
        1 => 30,
        2 => 20,
        3 => 10,
    ]);

    $books = Book::all();

    $this->assertEquals(30, $books->firstWhere('id', 1)->sort);
    $this->assertEquals(20, $books->firstWhere('id', 2)->sort);
    $this->assertEquals(10, $books->firstWhere('id', 3)->sort);
});