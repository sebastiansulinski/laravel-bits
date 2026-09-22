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
 * Run the callback with the application reporting the given environment,
 * which is how Artisan's --env flag reaches the container at runtime.
 */
function withApplicationEnvironment(string $environment, Closure $callback): void
{
    $original = app()->environment();

    try {
        app()->instance('env', $environment);

        $callback();
    } finally {
        app()->instance('env', $original);
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

/**
 * Forget any configured value, so the packaged default is merged back in.
 */
function forgetPackageConfiguration(): void
{
    config()->set('laravel-bits', []);
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

it('leaves the key unset whatever the environment is named', function (string $environment) {

    $configuration = packageConfiguration([
        'APP_ENV' => $environment,
        'LARAVEL_BITS_STRICT_MODELS' => null,
    ]);

    expect($configuration)->toHaveKey('strict_models')
        ->and($configuration['strict_models'])->toBeNull();

})->with([
    'production' => ['production'],
    'local' => ['local'],
    'user acceptance testing' => ['uat'],
]);

it('passes the environment variable through to the key', function (string $override, bool $expected) {

    expect(packageConfiguration([
        'APP_ENV' => 'local',
        'LARAVEL_BITS_STRICT_MODELS' => $override,
    ])['strict_models'])->toBe($expected);

})->with([
    'false' => ['false', false],
    'true' => ['true', true],
]);

it('follows the application environment when the key is not set', function (string $environment, bool $expected) {

    forgetPackageConfiguration();

    Model::shouldBeStrict(! $expected);

    withApplicationEnvironment($environment, fn () => registerPackage());

    expect(Model::preventsLazyLoading())->toBe($expected)
        ->and(Model::preventsSilentlyDiscardingAttributes())->toBe($expected)
        ->and(Model::preventsAccessingMissingAttributes())->toBe($expected);

})->with([
    'production is not strict' => ['production', false],
    'local is strict' => ['local', true],
    'user acceptance testing is strict' => ['uat', true],
]);

it('follows the application environment rather than the APP_ENV variable', function (
    string $applicationEnvironment,
    string $variable,
    bool $expected
) {

    forgetPackageConfiguration();

    Model::shouldBeStrict(! $expected);

    withEnvironment([
        'APP_ENV' => $variable,
        'LARAVEL_BITS_STRICT_MODELS' => null,
    ], fn () => withApplicationEnvironment($applicationEnvironment, fn () => registerPackage()));

    expect(Model::preventsLazyLoading())->toBe($expected)
        ->and(Model::preventsSilentlyDiscardingAttributes())->toBe($expected)
        ->and(Model::preventsAccessingMissingAttributes())->toBe($expected);

})->with([
    'an --env override away from production is strict' => ['local', 'production', true],
    'an --env override onto production is not strict' => ['production', 'local', false],
]);

it('defers to the application environment when the variable resolves to null', function () {

    forgetPackageConfiguration();

    Model::shouldBeStrict(true);

    withEnvironment([
        'LARAVEL_BITS_STRICT_MODELS' => 'null',
    ], fn () => withApplicationEnvironment('production', fn () => registerPackage()));

    expect(Model::preventsLazyLoading())->toBeFalse()
        ->and(Model::preventsSilentlyDiscardingAttributes())->toBeFalse()
        ->and(Model::preventsAccessingMissingAttributes())->toBeFalse();
});

it('casts an explicit environment variable value', function (string $override, bool $expected) {

    forgetPackageConfiguration();

    Model::shouldBeStrict(! $expected);

    withEnvironment([
        'LARAVEL_BITS_STRICT_MODELS' => $override,
    ], fn () => registerPackage());

    expect(Model::preventsLazyLoading())->toBe($expected)
        ->and(Model::preventsSilentlyDiscardingAttributes())->toBe($expected)
        ->and(Model::preventsAccessingMissingAttributes())->toBe($expected);

})->with([
    'false disables' => ['false', false],
    'zero disables' => ['0', false],
    'true enables' => ['true', true],
    'one enables' => ['1', true],
    'an unrecognised value fails towards strict' => ['definitely', true],
]);

it('honours an explicit true where the environment alone would not', function () {

    config()->set('laravel-bits.strict_models', true);

    Model::shouldBeStrict(false);

    withApplicationEnvironment('production', fn () => registerPackage());

    expect(Model::preventsLazyLoading())->toBeTrue()
        ->and(Model::preventsSilentlyDiscardingAttributes())->toBeTrue()
        ->and(Model::preventsAccessingMissingAttributes())->toBeTrue();
});

it('honours an explicit false where the environment alone would not', function () {

    config()->set('laravel-bits.strict_models', false);

    Model::shouldBeStrict(true);

    withApplicationEnvironment('local', fn () => registerPackage());

    expect(Model::preventsLazyLoading())->toBeFalse()
        ->and(Model::preventsSilentlyDiscardingAttributes())->toBeFalse()
        ->and(Model::preventsAccessingMissingAttributes())->toBeFalse();
});
