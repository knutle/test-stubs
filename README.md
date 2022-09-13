# TestStubs
Provides simple stub handling for test cases

## Installation
Install the package
```shell script
composer require knutle/test-stubs
```

## Usage
See [tests](./tests).

## Testing
Run test suite
```bash
composer test
```

## Deployment 
Whenever a version tag for a release candidate is pushed, and all tests pass, it will be promoted to the next available stable minor, which in turn will trigger the workflow one final time.

**Version tags need to be fully qualified, (for example v1.1 will be rejected, but v1.1.0 is OK)**

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.