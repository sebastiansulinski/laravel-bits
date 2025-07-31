<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use LaravelBits\Data\UpdateManySet;

beforeEach(function () {
    Schema::create('books', function ($table) {
        $table->id();
        $table->string('title');
        $table->integer('sort');
        $table->integer('score');
        $table->timestamps();
    });
});

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'title',
        'sort',
        'score',
    ];
}

it('can update many records with single set query', function () {

    Book::insert([
        [
            'id' => 1,
            'title' => 'The Hobbit',
            'sort' => 1,
            'score' => 10,
        ],
        [
            'id' => 2,
            'title' => 'The Eternal Sunshine of the Spotless Mind',
            'sort' => 2,
            'score' => 20,
        ],
        [
            'id' => 3,
            'title' => 'Mary Poppins',
            'sort' => 3,
            'score' => 30,
        ],
    ]);

    $this->assertDatabaseCount(Book::class, 3);

    Book::updateMany('id', new UpdateManySet(
        column: 'sort',
        data: [
            1 => 30,
            2 => 20,
            3 => 10,
        ]
    ));

    $books = Book::all();

    $this->assertCount(3, $books);

    $this->assertEquals(30, $books->firstWhere('id', 1)->sort);
    $this->assertEquals(20, $books->firstWhere('id', 2)->sort);
    $this->assertEquals(10, $books->firstWhere('id', 3)->sort);
});

it('can update many records with multiple set query', function () {

    Book::insert([
        [
            'id' => 1,
            'title' => 'The Hobbit',
            'sort' => 1,
            'score' => 10,
        ],
        [
            'id' => 2,
            'title' => 'The Eternal Sunshine of the Spotless Mind',
            'sort' => 2,
            'score' => 20,
        ],
        [
            'id' => 3,
            'title' => 'Mary Poppins',
            'sort' => 3,
            'score' => 30,
        ],
    ]);

    $this->assertDatabaseCount(Book::class, 3);

    Book::updateMany(
        'id',
        [
            new UpdateManySet(
                column: 'sort',
                data: [
                    1 => 30,
                    2 => 20,
                    3 => 10,
                ]
            ),
            new UpdateManySet(
                column: 'score',
                data: [
                    1 => 111,
                    2 => 222,
                    3 => 333,
                ]
            ),
        ],
    );

    $books = Book::all();

    $this->assertCount(3, $books);

    $this->assertEquals(30, $books->firstWhere('id', 1)->sort);
    $this->assertEquals(111, $books->firstWhere('id', 1)->score);
    $this->assertEquals(20, $books->firstWhere('id', 2)->sort);
    $this->assertEquals(222, $books->firstWhere('id', 2)->score);
    $this->assertEquals(10, $books->firstWhere('id', 3)->sort);
    $this->assertEquals(333, $books->firstWhere('id', 3)->score);
});
