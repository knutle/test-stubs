<?php

use Knutle\TestStubs\HasStubs;
use Knutle\TestStubs\InteractsWithStubs;

test('can get stub path with default base path', function () {
    expect((new class() implements HasStubs { use InteractsWithStubs; })->getStubPath('stub-name'))
        ->toEndWith('/test-stubs/src/stubs/stub-name');
});

test('can get stub path with temp base path', function () {
    expect(stubClassFake()->getStubPath('test'))
        ->toEndWith('/tests/stubs/test');
});

test('can get stub contents with temp base path', function () {
    $class = stubClassFake();

    expect($class->putStub('test', 'test stub contents'))->toBeTrue();
    expect($class->getStub('test'))->toBe('test stub contents');
});

test('can return false when no stub contents found', function () {
    $class = stubClassFake();

    expect($class->getStub('test'))->toBeFalse();
    expect($class->putStub('test', 'test stub contents'))->toBeTrue();
    expect($class->getStub('test'))->toBeTruthy();
});
