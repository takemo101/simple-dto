<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleDTO\Attributes\Ignore;
use Takemo101\SimpleDTO\Attributes\Rename;
use Takemo101\SimpleDTO\ObjectToArrayAdapter;

/**
 * ObjectToArrayAdapter class test
 */
class ObjectToArrayAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function toArray__OK()
    {
        $object = new class(
            'a',
            'b',
            'c',
            'd',
        )
        {
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
        };

        $adapter = new ObjectToArrayAdapter($object);
        $array = $adapter->toArray();

        $this->assertEquals('a', $array['a']);
        $this->assertEquals('b', $array['怪力']);
        $this->assertFalse(isset($array['c']));
        $this->assertFalse(isset($array['d']));
    }

    /**
     * @test
     */
    public function fromObjectToArray__OK()
    {
        $object = new class(
            'a',
            'b',
            'c',
            'd',
        )
        {
            public function __construct(
                public string $a,
                #[Rename('Bbb')]
                public string $b,
                #[Rename('CCC')]
                protected string $c,
                private string $d,
            ) {
                //
            }
        };

        $array = ObjectToArrayAdapter::fromObjectToArray($object);

        $this->assertEquals('a', $array['a']);
        $this->assertEquals('b', $array['Bbb']);
        $this->assertFalse(isset($array['CCC']));
        $this->assertFalse(isset($array['d']));
    }
}
