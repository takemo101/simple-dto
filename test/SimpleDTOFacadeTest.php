<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleDTO\Attributes\Getter;
use Takemo101\SimpleDTO\Attributes\Ignore;
use Takemo101\SimpleDTO\Attributes\Rename;
use Takemo101\SimpleDTO\SimpleDTOFacade;

/**
 * SimpleDTOFacade class test
 */
class SimpleDTOFacadeTest extends TestCase
{
    /**
     * @test
     */
    public function findMethod__OK()
    {
        $object = new class(
            'a',
        )
        {
            public function __construct(
                public string $a,
            ) {
                //
            }

            #[Getter]
            public function foo(): string
            {
                return 'foo';
            }

            public function bar()
            {
                return 'bar';
            }
        };

        $facade = new SimpleDTOFacade($object);

        $this->assertEquals('foo', $facade->findMethod('foo')->output());
        $this->assertNull($facade->findMethod('bar'));
        $this->assertNull($facade->findMethod('a'));
    }

    /**
     * @test
     */
    public function toArray__OK()
    {
        $object = new class(
            'a',
            'b',
            'c',
        )
        {
            public function __construct(
                #[Rename('怪力')]
                public string $a,
                #[Ignore]
                public string $b,
                private string $c,
            ) {
                //
            }

            #[Getter]
            public function foo(): string
            {
                return 'foo';
            }

            public function bar()
            {
                return 'bar';
            }
        };

        $facade = new SimpleDTOFacade($object);
        $array = $facade->toArray();

        $this->assertEquals('a', $array['怪力']);
        $this->assertEquals('foo', $array['foo']);
        $this->assertFalse(isset($array['b']));
        $this->assertFalse(isset($array['c']));
        $this->assertFalse(isset($array['bar']));
    }
}
