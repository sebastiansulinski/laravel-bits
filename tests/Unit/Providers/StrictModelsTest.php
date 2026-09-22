<?php

use Illuminate\Database\Eloquent\Model;
use LaravelBits\Providers\LaravelBitsServiceProvider;

/**
 * Apply a single environment variable across every adapter Laravel reads from,
 * removing it altogether when the value is null.
 */
function applyEnvironmentVariable(string $key, ?string $server, ?string $environment, ?string $process): void
{
    if (is_null($server)) {
        unset($_SERVER[$key]);
    } else {
        $_SERVER[$key] = $server;
    }

    if (is_null($environment)) {
        unset($_ENV[$key]);
    } else {
        $_ENV[$key] = $environment;
    }

    if (is_null($process)) {
        putenv($key);
    } else {
        putenv("{$key}={$process}");
    }
}

/**
 * Run the callback with the given environment variables in place, restoring
 * the original environment afterwards.
 *
 * @param  array<string, string|null>  $environment
 */
function withEnvironment(array $environment, Closure $callback): mixed
{
    $original = [];

    foreach (array_keys($environment) as $key) {
        $original[$key] = [
            $_SERVER[$key] ?? null,
            $_ENV[$key] ?? null,
            getenv($key) === false ? null : getenv($key),
        ];
    }

    try {
        foreach ($environment as $key => $value) {
            applyEnvironmentVariable($key, $value, $value, $value);
        }

        return $callback();
    } finally {
        foreach ($original as $key => [$server, $environmentValue, $process]) {
            applyEnvironmentVariable($key, $server, $environmentValue, $process);
        }
    }
}

/**
 * Evaluate the packaged configuration file under the given environment.
 *
 * @param  array<string, string|null>  $environment
 * @return array<string, mixed>
 */
function packageConfiguration(array $environment): array
{
    return withEnvironment(
        $environment,
        fn (): array => require __DIR__.'/../../../config/laravel-bits.php'
    );
}

/**
 * Re-register and re-boot the package service provider against the current configuration.
 */
function registerPackage(): void
{
    app()->register(LaravelBitsServiceProvider::class, force: true);
}

$strictModelState = null;

beforeEach(function () use (&$strictModelState) {
    $strictModelState = [
        Model::preventsLazyLoading(),
        Model::preventsSilentlyDiscardingAttributes(),
        Model::preventsAccessingMissingAttributes(),
    ];
});

afterEach(function () use (&$strictModelState) {
    [$lazyLoading, $discarding, $missing] = $strictModelState;

    Model::preventLazyLoading($lazyLoading);
    Model::preventSilentlyDiscardingAttributes($discarding);
    Model::preventAccessingMissingAttributes($missing);
});

it('derives the default from the application environment', function (?string $environment, bool $expected) {

    expect(packageConfiguration([
        'APP_ENV' => $environment,
        'LARAVEL_BITS_STRICT_MODELS' => null,
    ]))->toHaveKey('strict_models', $expected);

})->with([
    'production is not strict' => ['production', false],
    'local is strict' => ['local', true],
    'staging is strict' => ['staging', true],
    'user acceptance testing is strict' => ['uat', true],
    'an absent environment falls back to production' => [null, false],
]);

it('lets the environment variable override the default', function (?string $environment, string $override, bool $expected) {

    expect(packageConfiguration([
        'APP_ENV' => $environment,
        'LARAVEL_BITS_STRICT_MODELS' => $override,
    ]))->toHaveKey('strict_models', $expected);

})->with([
    'disabled outside production' => ['local', 'false', false],
    'disabled outside production with zero' => ['local', '0', false],
    'enabled in production' => ['production', 'true', true],
    'enabled in production with one' => ['production', '1', true],
    'an unrecognised value fails towards strict' => ['production', 'definitely', true],
]);

it('enables strict models when the configuration is enabled', function () {

    config()->set('laravel-bits.strict_models', true);

    Model::shouldBeStrict(false);

    registerPackage();

    expect(Model::preventsLazyLoading())->toBeTrue()
        ->and(Model::preventsSilentlyDiscardingAttributes())->toBeTrue()
        ->and(Model::preventsAccessingMissingAttributes())->toBeTrue();
});

it('disables strict models when the configuration is disabled', function () {

    config()->set('laravel-bits.strict_models', false);

    Model::shouldBeStrict(true);

    registerPackage();

    expect(Model::preventsLazyLoading())->toBeFalse()
        ->and(Model::preventsSilentlyDiscardingAttributes())->toBeFalse()
        ->and(Model::preventsAccessingMissingAttributes())->toBeFalse();
});

it('honours the configuration over an environment pointing the other way', function () {

    Model::shouldBeStrict(false);

    withEnvironment([
        'APP_ENV' => 'production',
        'LARAVEL_BITS_STRICT_MODELS' => null,
    ], function () {
        config()->set('laravel-bits.strict_models', true);

        registerPackage();
    });

    expect(Model::preventsLazyLoading())->toBeTrue()
        ->and(Model::preventsSilentlyDiscardingAttributes())->toBeTrue()
        ->and(Model::preventsAccessingMissingAttributes())->toBeTrue();
});

it('falls back to the packaged default when the application provides no value', function () {

    config()->set('laravel-bits', []);

    Model::shouldBeStrict(false);

    registerPackage();

    expect(config('laravel-bits.strict_models'))->toBeTrue()
        ->and(Model::preventsLazyLoading())->toBeTrue()
        ->and(Model::preventsSilentlyDiscardingAttributes())->toBeTrue()
        ->and(Model::preventsAccessingMissingAttributes())->toBeTrue();
});

it('applies the packaged default without strict models in production', function () {

    Model::shouldBeStrict(true);

    withEnvironment([
        'APP_ENV' => 'production',
        'LARAVEL_BITS_STRICT_MODELS' => null,
    ], function () {
        config()->set('laravel-bits', []);

        registerPackage();
    });

    expect(config('laravel-bits.strict_models'))->toBeFalse()
        ->and(Model::preventsLazyLoading())->toBeFalse()
        ->and(Model::preventsSilentlyDiscardingAttributes())->toBeFalse()
        ->and(Model::preventsAccessingMissingAttributes())->toBeFalse();
});
