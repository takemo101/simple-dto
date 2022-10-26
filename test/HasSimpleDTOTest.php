<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleDTO\Attributes\Getter;
use Takemo101\SimpleDTO\Attributes\Ignore;
use Takemo101\SimpleDTO\Attributes\Rename;
use Takemo101\SimpleDTO\Traits\HasSimpleDTO;
use Takemo101\SimpleDTO\Traits\MissingGetterMethodException;

/**
 * HasSimpleDTO trait test
 */
class HasSimpleDTOTest extends TestCase
{
    /**
     * @test
     */
    public function __get__OK()
    {
        $dto = new TestDTO(
            'a',
            'b',
            'c',
            'd',
        );

        $this->assertEquals('foo', $dto->foo);
        $this->assertEquals('bar', $dto->バー);
        $this->assertNull($dto->bee);
    }

    /**
     * @test
     */
    public function toArray__NG()
    {
        $dto = new TestDTO(
            'a',
            'b',
            'c',
            'd',
        );

        $array = $dto->toArray();

        $this->assertEquals('a', $array['a']);
        $this->assertEquals('b', $array['怪力']);
        $this->assertEquals('foo', $array['foo']);
        $this->assertEquals('bar', $array['バー']);

        $this->assertFalse(isset($array['c']));
        $this->assertFalse(isset($array['d']));
        $this->assertFalse(isset($array['bee']));
    }
}

class TestDTO
{
    use HasSimpleDTO;

    public function __construct(
        public string $a,
        #[Rename('怪力')]
        public string $b,
        #[Ignore]
        public string $c,
        private string $d,
    ) {
        //
    }

    #[Getter]
    public function foo(): string
    {
        return 'foo';
    }

    #[Getter('バー')]
    public function bar()
    {
        return 'bar';
    }

    public function bee()
    {
        return 'bar';
    }
}
