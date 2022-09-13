<?php

namespace Knutle\TestStubs;

interface HasStubs
{
    public static function hasStub(string $stubPath): bool;
    public static function getStubPath(string $stubPath): string;
    public static function getStub(string $stubPath): bool|string;
    public static function getJsonStub(string $stubPath): array;
    public static function putStub(string $stubPath, mixed $data): bool|string;
    public static function putJsonStub(string $stubPath, mixed $data): bool|string;
    public static function allStubs(): array;
}
