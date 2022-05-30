<?php


namespace Knutle\TestStubs;

use Symfony\Component\Filesystem\Path;
use Throwable;

trait InteractsWithStubs
{
    protected static function getStubsBasePath(): string
    {
        return Path::join(
            __DIR__,
                'stubs',
        );
    }

    public static function getStubPath(string $stubPath): string
    {
        return Path::join(
            static::getStubsBasePath(),
            $stubPath
        );
    }

    public static function getStub(string $stubPath): bool|string
    {
        try {
            return file_get_contents(
                static::getStubPath($stubPath)
            );
        } catch (Throwable) {
            return false;
        }
    }

    public static function putStub(string $stubPath, mixed $data): bool
    {
        return file_put_contents(
            static::getStubPath($stubPath),
            $data
        ) !== false;
    }
}