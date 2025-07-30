<?php

use LaravelBits\Traits\Serialisable;

it('can serialize to json', function () {

    $class = new class
    {
        use Serialisable;

        public function toArray(): array
        {
            return [
                'name' => 'John Doe',
                'email' => 'john@doe.com',
            ];
        }
    };

    expect($class->toJson())
        ->toBe(json_encode([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
        ]))
        ->and((string) $class)
        ->toBe(json_encode([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
        ]));
});
