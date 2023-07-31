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
## Basic usage
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

## How to add functionality to convert DTO to array
```php
use Takemo101\SimpleDTO\SimpleDTOFacade;

// A class that implements the DTOTransformer interface can be given as an argument.
// If no arguments are given, the default implementation will be set.
SimpleDTOFacade::setup();

class FirstDTO
{
    use HasSimpleDTO;

    public function __construct(
        public SecondDTO $second,
    ) {
        //
    }
}

class SecondDTO
{
    use HasSimpleDTO;

    public function __construct(
        public string $text,
    ) {
        //
    }
}

$dto = new FirstDTO(new SecondDTO('text'));

// DTO is arrayed by default DTOTransformer.
var_dump($dto->toArray()); // ['second' => ['text' => 'text']]

```


## How to reconstruct a DTO from an array

```php
use Takemo101\SimpleDTO\SimpleDTOFacade;

// omit...

// Reconstructs the DTO from the array, so it can be both arrayed and objectified
$dto = FirstDTO::fromArray(['second' => ['text' => 'text']]);

```
