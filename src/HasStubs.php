<?php

namespace Knutle\TestStubs;

interface HasStubs
{
    public static function getStubPath(string $stubPath): string;
    public static function getStub(string $stubPath): bool|string;
    public static function putStub(string $stubPath, mixed $data): bool|string;
}