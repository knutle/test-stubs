<?php

namespace Knutle\TestStubs;

use Composer\InstalledVersions;
use Pest\Plugin;
use function func_get_args;
use function test;

if(InstalledVersions::isInstalled('pestphp/pest')) {
    Plugin::uses(InteractsWithStubs::class);

    function hasStub(string $stubPath): bool
    {
        return test()->hasStub(...func_get_args());
    }

    function getStubPath(string $stubPath): string
    {
        return test()->getStubPath(...func_get_args());
    }

    function getStub(string $stubPath): bool|string
    {
        return test()->getStub(...func_get_args());
    }

    function getJsonStub(string $stubPath): array
    {
        return test()->getJsonStub(...func_get_args());
    }

    function putStub(string $stubPath, mixed $data): bool|string
    {
        return test()->putStub(...func_get_args());
    }

    function putJsonStub(string $stubPath, mixed $data): bool|string
    {
        return test()->putJsonStub(...func_get_args());
    }

    function allStubs(): array
    {
        return test()->allStubs(...func_get_args());
    }
}
