<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleDTO\Attributes\Getter;
use Takemo101\SimpleDTO\Attributes\Ignore;
use Takemo101\SimpleDTO\Attributes\Rename;
use Takemo101\SimpleDTO\DTOAdapter;
use Takemo101\SimpleDTO\ValueConverter;

/**
 * DTOAdapter class test
 */
class DTOAdapterTest extends TestCase
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

        $adapter = new DTOAdapter($object);

        $this->assertEquals('foo', $adapter->findMethodOutput('foo')->output());
        $this->assertNull($adapter->findMethodOutput('bar'));
        $this->assertNull($adapter->findMethodOutput('a'));
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

        $adapter = new DTOAdapter($object);
        $array = $adapter->toArray(ValueConverter::empty());

        $this->assertEquals('a', $array['怪力']);
        $this->assertEquals('foo', $array['foo']);
        $this->assertFalse(isset($array['b']));
        $this->assertFalse(isset($array['c']));
        $this->assertFalse(isset($array['bar']));
    }
}
