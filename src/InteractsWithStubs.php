<?php


namespace Knutle\TestStubs;

use Illuminate\Support\Str;
use ReflectionClass;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Throwable;

trait InteractsWithStubs
{
    protected static function getStubsBasePath(): string
    {
        if(str_starts_with(static::class, "P\\")) {
            // Detected Pest test, will base stub path on parent TestCase

            $filename = (new ReflectionClass(static::class))->getParentClass()->getFileName();
        } else {
            $filename = (new ReflectionClass(static::class))->getFileName();
        }

        $descendantDir = dirname($filename);

        $dirSegments = explode(DIRECTORY_SEPARATOR, $descendantDir);

        while(true) {
            $path = DIRECTORY_SEPARATOR . Path::join(...[
                ...$dirSegments,
                'stubs',
            ]);

            if(is_dir($path)) {
                return $path;
            }

            array_pop($dirSegments);

            if(empty($dirSegments)) {
                break;
            }
        }

        $path = Path::join(
            $descendantDir,
            'stubs',
        );

        if(!is_dir($path)) {
            return Path::join(
                (explode(DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR, $path)[0] ?? null) ?? $path,
                'tests',
                'stubs'
            );
        }

        return $path;
    }

    public static function allStubs(): array
    {
        $basePath = static::getStubsBasePath();

        if(!is_dir($basePath)) {
            return [];
        }

        return collect(Finder::create()->files()->ignoreDotFiles(true)->in($basePath)->sortByName())
            ->keys()
            ->map(
                fn (string $path) => Str::replaceFirst(
                    DIRECTORY_SEPARATOR, '',
                    Str::replaceFirst(
                        $basePath, '',
                        $path
                    )
                )
            )->toArray();
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