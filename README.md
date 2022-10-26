# The Simple DTO

[![Testing](https://github.com/takemo101/simple-dto/actions/workflows/testing.yml/badge.svg)](https://github.com/takemo101/simple-dto/actions/workflows/testing.yml)
[![PHPStan](https://github.com/takemo101/simple-dto/actions/workflows/phpstan.yml/badge.svg)](https://github.com/takemo101/simple-dto/actions/workflows/phpstan.yml)
[![Validate Composer](https://github.com/takemo101/simple-dto/actions/workflows/composer.yml/badge.svg)](https://github.com/takemo101/simple-dto/actions/workflows/composer.yml)

The Simple DTO is a library to create simple data objects.   
Enjoy!

## Installation
Execute the following composer command.
```
composer require takemo101/simple-dto
```
## How to use
Please use as follows

```php
<?php

use Takemo101\SimpleDTO\Attributes\Getter;
use Takemo101\SimpleDTO\Attributes\Ignore;
use Takemo101\SimpleDTO\Attributes\Rename;
use Takemo101\SimpleDTO\Traits\HasSimpleDTO;

class MyDTO
{
    // Use this trait to support arraying and getter methods
    use HasSimpleDTO;

    public function __construct(
        public string $a,
        // Rename array keys
        #[Rename('bb')]
        public string $b,
        // ignore converting to array
        #[Ignore]
        public string $c,
    ) {
        //
    }

    // When set to a getter method, it can be referenced as a property
    #[Getter]
    public function foo(): string
    {
        return 'foo';
    }

    // You can change the name you refer to as a property
    #[Getter('var')]
    public function bar()
    {
        return 'bar';
    }
}

$dto = new MyDTO('a', 'b', 'c');

echo $dto->foo; // foo
echo $dto->var; // bar

// Convert to array
var_dump($dto->toArray()); // ['a' => 'a', 'bb' => 'b', 'foo' => 'foo', 'var' => 'bar']
```

```
