<?php

use Illuminate\Support\Collection;
use LaravelBits\Traits\EnhancedEnums;

enum Status: string
{
    use EnhancedEnums;

    case Active = 'active';
    case Pending = 'pending';
    case Closed = 'closed';

    public function label(): string
    {
        return ucfirst($this->value).' Status';
    }
}

enum BassBrand: string
{
    use EnhancedEnums;

    case Fender = 'fender';
    case Ibanez = 'ibanez';
    case Warwick = 'warwick';
}

test('correctly returns enum values as array', function () {
    expect(Status::values())
        ->toEqual(['active', 'pending', 'closed'])
        ->and(Status::values(Status::except([Status::Active, Status::Closed])))
        ->toEqual(['pending']);
});

test('correctly returns enum names as array', function () {
    expect(Status::names())
        ->toEqual(['Active', 'Pending', 'Closed'])
        ->and(Status::names(Status::except([Status::Active, Status::Closed])))
        ->toEqual(['Pending']);
});

test('correctly returns enum case as array', function () {
    expect(Status::Active->toArray())->toEqual([
        'name' => 'Active Status',
        'value' => 'active',
    ]);
});

test('correctly returns enum as an array of options', function () {
    expect(Status::options())
        ->toEqual([
            [
                'name' => 'Active Status',
                'value' => 'active',
            ],
            [
                'name' => 'Pending Status',
                'value' => 'pending',
            ],
            [
                'name' => 'Closed Status',
                'value' => 'closed',
            ],
        ])
        ->and(Status::options(Status::except(Status::Active)))
        ->toEqual([
            [
                'name' => 'Pending Status',
                'value' => 'pending',
            ],
            [
                'name' => 'Closed Status',
                'value' => 'closed',
            ],
        ])
        ->and(BassBrand::options())
        ->toEqual([
            [
                'name' => 'Fender',
                'value' => 'fender',
            ],
            [
                'name' => 'Ibanez',
                'value' => 'ibanez',
            ],
            [
                'name' => 'Warwick',
                'value' => 'warwick',
            ],
        ]);
});

it('correctly returns cases as collection', function () {

    expect(Status::collection())
        ->toEqual(new Collection(Status::cases()));
});
