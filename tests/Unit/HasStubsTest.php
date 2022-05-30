<?php

use Knutle\TestStubs\Tests\Fakes\FakeStubOwner;
use Symfony\Component\Filesystem\Path;

test('can list all available stubs', function () {
    expect($this->allStubs()[0])->toEqual('example-stub.txt');
});

test('can get stub with fallback base path', function () {
    expect($this->getStubPath('example-stub.txt'))
        ->toEndWith(Path::join(
            '/test-stubs/tests/stubs/example-stub.txt'
        ));

    expect($this->getStub('example-stub.txt'))
        ->toEqual("example");
});

test('can get stub from adjacent stubs dir', function () {
    expect(FakeStubOwner::getStubPath('test.txt'))
        ->toEndWith('/test-stubs/tests/Fakes/stubs/test.txt');

    expect(FakeStubOwner::getStub('test.txt'))
        ->toEqual('testing one two');
});

test('can list stubs recursively', function () {
    expect(FakeStubOwner::allStubs())
        ->toEqual([
            Path::join('more/abc.txt'),
            'test.txt',
        ]);
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
