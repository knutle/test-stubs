<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

// uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

use Illuminate\Support\Str;
use Knutle\TestStubs\HasStubs;
use Knutle\TestStubs\InteractsWithStubs;
use Symfony\Component\Filesystem\Path;

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function stubClassFake(): HasStubs
{
    return tap(
        new class() implements HasStubs {
            use InteractsWithStubs;

            protected static ?string $stubsBasePath;

            protected static function getStubsBasePath(): string
            {
                return tap(
                    static::$stubsBasePath ?? static::$stubsBasePath = Path::join(sys_get_temp_dir(), Str::random(5), 'tests', 'stubs'),
                    function (string $path) {
                        if(!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                    }
                );
            }

            public static function reset()
            {
                static::$stubsBasePath = null;
            }
        },
        fn (HasStubs $class) => $class::reset()
    );
}
