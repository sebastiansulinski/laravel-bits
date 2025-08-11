<?php

use Illuminate\Support\Arr;

it('correctly converts array keys to camel case', function () {

    expect(Arr::keysToCamel([
        'token_type' => 'Bearer',
        'created_at' => '2021-01-01 13:04:46',
        'user_data' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ],
    ]))
        ->toBe([
            'tokenType' => 'Bearer',
            'createdAt' => '2021-01-01 13:04:46',
            'userData' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);
});

it('correctly converts array keys to snake case', function () {

    expect(Arr::keysToSnake([
        'tokenType' => 'Bearer',
        'createdAt' => '2021-01-01 13:04:46',
        'userData' => [
            'firstName' => 'John',
            'lastName' => 'Doe',
        ],

    ]))
        ->toBe([
            'token_type' => 'Bearer',
            'created_at' => '2021-01-01 13:04:46',
            'user_data' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
            ],
        ]);
});
