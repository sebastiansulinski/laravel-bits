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

enum ApiResource: string
{
    use EnhancedEnums;

    // PascalCase
    case ApiResources = 'api_resources';
    case UserProfile = 'user_profile';
    case OrderHistoryDetails = 'order_history_details';

    // camelCase
    case orderHistory = 'order_history';
    case shippingAddress = 'shipping_address';

    // Single word
    case Settings = 'settings';

    // With numbers
    case Version2Release = 'version_2_release';

    // snake_case
    case payment_method = 'payment_method';
    case billing_address_details = 'billing_address_details';
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

it('converts PascalCase enum names to space-separated labels', function () {
    expect(ApiResource::ApiResources->label())->toBe('Api Resources')
        ->and(ApiResource::UserProfile->label())->toBe('User Profile')
        ->and(ApiResource::OrderHistoryDetails->label())->toBe('Order History Details');
});

it('converts camelCase enum names to space-separated labels', function () {
    expect(ApiResource::orderHistory->label())->toBe('Order History')
        ->and(ApiResource::shippingAddress->label())->toBe('Shipping Address');
});

it('handles single word enum names', function () {
    expect(ApiResource::Settings->label())->toBe('Settings');
});

it('handles enum names with numbers', function () {
    expect(ApiResource::Version2Release->label())->toBe('Version2 Release');
});

it('converts snake_case enum names to space-separated labels', function () {
    expect(ApiResource::payment_method->label())->toBe('Payment Method')
        ->and(ApiResource::billing_address_details->label())->toBe('Billing Address Details');
});
