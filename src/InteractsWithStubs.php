<?php


namespace Knutle\TestStubs;

use ReflectionClass;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Throwable;

trait InteractsWithStubs
{
    public static string $directorySeparator = DIRECTORY_SEPARATOR;

    protected static function getStubsBasePath(): string
    {
        if(str_starts_with(static::class, "P\\")) {
            // Detected Pest test, will base stub path on parent TestCase

            $filename = (new ReflectionClass(static::class))->getParentClass()->getFileName();
        } else {
            $filename = (new ReflectionClass(static::class))->getFileName();
        }

        $descendantDir = dirname($filename);

        $dirSegments = explode('/', Path::normalize($descendantDir));

        while(true) {
            $path = Path::join(...[
                ...$dirSegments,
                'stubs',
            ]);

            if(!Path::isAbsolute($path)) {
                $path = Path::makeAbsolute($path, '/');
            }

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
            // fallback to find the closest parent dir containing a vendor dir
            // should be fairly likely that is our package base path, and from
            // there we can look for tests/stubs to use as a fallback

            $packageBase = explode('/vendor/', $path)[0] ?? null;

            if(is_null($packageBase)) {
                return $path;
            }

            return Path::join(
                 $packageBase,
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
                fn (string $path) => Path::makeRelative($path, $basePath)
            )->toArray();
    }

    public static function getStubPath(string $stubPath): string
    {
        $path = Path::join(
            static::getStubsBasePath(),
            $stubPath
        );

        if(!str_contains($path, static::$directorySeparator)) {
            $path = str_replace('/', static::$directorySeparator, $path);
        }

        return $path;
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
        $stubFilePath = static::getStubPath($stubPath);
        $stubDirPath = dirname($stubFilePath);

        if(!file_exists($stubDirPath)) {
            mkdir($stubDirPath, recursive: true);
        }

        return file_put_contents(
            $stubFilePath,
            $data
        ) !== false;
    }
}