<?php

use Knutle\TestStubs\Tests\Fakes\FakeStubOwner;

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

test('can create parent dir recursively when putting stub contents', function () {
    $class = stubClassFake();

    $stubName = 'put-recursive-test/'.time().'/test.txt';

    expect($class::hasStub($stubName))
        ->toBeFalse()
        ->and($class::putStub($stubName, 'test recursive stub contents'))
        ->toBeTrue()
        ->and($class::getStub($stubName))
        ->toBe('test recursive stub contents')
        ->and($class::hasStub($stubName))
        ->toBeTrue();
});

test('can get and put json stubs', function () {
    $class = stubClassFake();

    expect($class::putJsonStub('test-json.json', ['test' => 'hello']))
        ->toBeTrue()
        ->and($class::getJsonStub('test-json.json'))
        ->toBe(['test' => 'hello'])
        ->and($class::putJsonStub('test-json.json', '{"test2": "hei"}'))
        ->toBeTrue()
        ->and($class::getJsonStub('test-json.json'))
        ->toBe(['test2' => 'hei']);
});

test('will return default value if stub file not found', function () {
    $class = stubClassFake();

    expect($class::getStub('not-found'))
        ->toEqual('')
        ->and($class::getJsonStub('not-found'))
        ->toEqual([]);
});
