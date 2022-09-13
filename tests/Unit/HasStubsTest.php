<?php

use Knutle\TestStubs\Tests\Fakes\FakeStubOwner;

test('can list all available stubs', function () {
    expect($this->allStubs()[0])->toEqual('example-stub.txt');
});

test('can get stub with fallback base path', function () {
    expect($this->getStubPath('example-stub.txt'))
        ->toEndWith(
            str_replace([ '/', '\\' ], DIRECTORY_SEPARATOR, "/test-stubs/tests/stubs/example-stub.txt")
        )
        ->and($this->getStub('example-stub.txt'))
        ->toEqual("example");

});

test('can use custom directory separator when outputting paths', function () {
    FakeStubOwner::$directorySeparator = "\\";

    expect(FakeStubOwner::getStubPath('test.txt'))
        ->toEndWith(
            '\\test-stubs\\tests\\Fakes\\stubs\\test.txt'
        );

    FakeStubOwner::$directorySeparator = DIRECTORY_SEPARATOR;
});

test('can get stub from adjacent stubs dir', function () {
    expect(FakeStubOwner::getStubPath('test.txt'))
        ->toEndWith(
            str_replace([ '/', '\\' ], DIRECTORY_SEPARATOR, '/test-stubs/tests/Fakes/stubs/test.txt')
        )
        ->and(FakeStubOwner::getStub('test.txt'))
        ->toEqual('testing one two');

});

test('can list stubs recursively', function () {
    expect(FakeStubOwner::allStubs())
        ->toEqual([
            'more/abc.txt',
            'test.txt',
        ]);
});

test('can list stubs with normalized relative paths even when using backslash as directory separator', function () {
    FakeStubOwner::$directorySeparator = "\\";

    expect(FakeStubOwner::allStubs())
        ->toEqual([
            'more/abc.txt',
            'test.txt',
        ]);

    FakeStubOwner::$directorySeparator = DIRECTORY_SEPARATOR;
});

test('can get stub path with temp base path', function () {
    expect(stubClassFake()::getStubPath('test'))
        ->toEndWith(
            str_replace(['/', '\\'], DIRECTORY_SEPARATOR, '/tests/stubs/test')
        );
});

test('can get stub contents with temp base path', function () {
    $class = stubClassFake();

    expect($class::putStub('test', 'test stub contents'))
        ->toBeTrue()
        ->and($class::getStub('test'))
        ->toBe('test stub contents');
});

test('can return false when no stub contents found', function () {
    $class = stubClassFake();

    expect($class::getStub('test'))
        ->toBeFalse()
        ->and($class::putStub('test', 'test stub contents'))
        ->toBeTrue()
        ->and($class::getStub('test'))
        ->toBeTruthy();
});

