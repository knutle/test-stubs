<?php

use function Knutle\TestStubs\allStubs;
use function Knutle\TestStubs\getJsonStub;
use function Knutle\TestStubs\getStub;
use function Knutle\TestStubs\getStubPath;
use function Knutle\TestStubs\hasStub;
use function Knutle\TestStubs\putJsonStub;
use function Knutle\TestStubs\putStub;

test('can list all available stubs', function () {
    expect(allStubs()[0])->toEqual('example-stub.txt');
});

test('can get stub with fallback base path', function () {
    expect(getStubPath('example-stub.txt'))
        ->toEndWith(
            str_replace([ '/', '\\' ], DIRECTORY_SEPARATOR, '/test-stubs/tests/stubs/example-stub.txt')
        )
        ->and(getStub('example-stub.txt'))
        ->toEqual('example');
});

test('can check if stub exists', function () {
    $stubName = 'ignore/'.time().'/test.txt';

    expect(hasStub($stubName))
        ->toBeFalse()
        ->and(putStub($stubName, 'test recursive stub contents'))
        ->toBeTrue()
        ->and(getStub($stubName))
        ->toBe('test recursive stub contents')
        ->and(hasStub($stubName))
        ->toBeTrue();
});

test('can get and put json stubs', function () {
    expect(putJsonStub('ignore/test-json.json', ['test' => 'hello']))
        ->toBeTrue()
        ->and(getJsonStub('ignore/test-json.json'))
        ->toBe(['test' => 'hello']);
});
