<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleDTO\Attributes\Getter;
use Takemo101\SimpleDTO\Attributes\Ignore;
use Takemo101\SimpleDTO\GetterMethodFinder;
use Takemo101\SimpleDTO\ValueConverter;

/**
 * GetterMethodFinder class test
 */
class GetterMethodFinderTest extends TestCase
{
    /**
     * @test
     */
    public function find_getter_method__OK()
    {
        $object = new class
        {
            #[Getter]
            public function foo(): string
            {
                return 'foo';
            }

            #[Getter('ber')]
            public function bar()
            {
                return 'bar';
            }

            #[Getter]
            protected function boo(): string
            {
                return 'boo';
            }

            public function bee(): string
            {
                return 'bee';
            }
        };

        $finder = GetterMethodFinder::fromObject($object);

        $this->assertEquals('foo', $finder->find('foo')->output());
        $this->assertEquals('bar', $finder->find('ber')->output());
        $this->assertEquals('boo', $finder->find('boo')->output());
        $this->assertNull($finder->find('bee'));
    }

    /**
     * @test
     */
    public function toArray__OK()
    {
        $object = new class
        {
            #[Getter]
            public function foo(): string
            {
                return 'foo';
            }

            #[Getter('bal')]
            public function bar(string $bal = 'bal'): string
            {
                return $bal;
            }

            public function bee(): string
            {
                return 'bee';
            }
        };

        $finder = GetterMethodFinder::fromObject($object);

        $array = $finder->toArray(ValueConverter::empty());

        $this->assertEquals('foo', $array['foo']);
        $this->assertEquals('bal', $array['bal']);
        $this->assertFalse(isset($array['bee']));
    }

    /**
     * @test
     */
    public function ignoreMethod__OK()
    {
        $object = new class
        {
            #[Getter]
            #[Ignore]
            public function foo(): string
            {
                return 'foo';
            }

            #[Getter('bal')]
            public function bar(string $bal = 'bal'): string
            {
                return $bal;
            }
        };

        $finder = GetterMethodFinder::fromObject($object);

        $this->assertNotNull($finder->find('foo'));

        $array = $finder->toArray(ValueConverter::empty());

        $this->assertArrayNotHasKey('foo', $array);
        $this->assertEquals('bal', $array['bal']);
    }
}
