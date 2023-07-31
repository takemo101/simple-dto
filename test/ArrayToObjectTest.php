<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleDTO\Attributes\Casts\EnumCast;
use Takemo101\SimpleDTO\Attributes\Casts\ToObjectsCast;
use Takemo101\SimpleDTO\Attributes\PropDefault;
use Takemo101\SimpleDTO\Contracts\ToObjectable;
use Takemo101\SimpleDTO\Traits\HasMinimalDTO;

/**
 * ArrayToObject class test
 */
class ArrayToObjectTest extends TestCase
{
    /**
     * @test
     */
    public function fromArray__OK()
    {
        $expected = [
            'a' => 'a',
            'b' => 'b',
            'c' => [
                'd' => 1,
                'e' => 2,
                'f' => [
                    'g' => 3,
                    'h' => 4,
                ]
            ]
        ];

        $object = ArrayToObjectParentClass::__fromArray($expected);

        $this->assertEquals($expected['a'], $object->a);
        $this->assertEquals($expected['b'], $object->b);
        $this->assertEquals($expected['c']['d'], $object->c->d);
        $this->assertEquals($expected['c']['e'], $object->c->e);
        $this->assertEquals($expected['c']['f']['g'], $object->c->f->g);
        $this->assertEquals($expected['c']['f']['h'], $object->c->f->h);
    }

    /**
     * @test
     */
    public function getDefaultValueWithFromArray__OK()
    {
        $object = ArrayToObjectParentClass::__fromArray([]);

        $this->assertEquals('aa', $object->a);
        $this->assertEquals('bb', $object->b);
        $this->assertEquals(1, $object->c->d);
        $this->assertEquals(1, $object->c->e);
        $this->assertEquals(2, $object->c->f->g);
        $this->assertEquals(1, $object->c->f->h);

        $expected = $object->__toArray();

        $this->assertEquals($expected['a'], $object->a);
        $this->assertEquals($expected['b'], $object->b);
        $this->assertEquals($expected['c']['d'], $object->c->d);
        $this->assertEquals($expected['c']['e'], $object->c->e);
        $this->assertEquals($expected['c']['f']['g'], $object->c->f->g);
        $this->assertEquals($expected['c']['f']['h'], $object->c->f->h);
    }

    /**
     * @test
     */
    public function generateAnObjectArrayWithFromArray__OK()
    {
        $expected = [
            'a' => 'a',
            'list' => [
                [
                    'g' => 1,
                    'h' => 2,
                ],
                [
                    'g' => 3,
                    'h' => 4,
                ],
            ],
        ];

        $object = ArrayToObjectParentArrayPropertyClass::__fromArray($expected);

        $this->assertEquals(ArrayToObjectEnum::from($expected['a']), $object->a);
        foreach ($expected['list'] as $key => $item) {
            $this->assertEquals($item, $object->list[$key]->__toArray());
        }
    }
}

enum ArrayToObjectEnum: string
{
    case A = 'a';
    case B = 'b';
}

class ArrayToObjectParentArrayPropertyClass
{
    use HasMinimalDTO;

    public function __construct(
        #[EnumCast(ArrayToObjectEnum::class)]
        public ArrayToObjectEnum $a = ArrayToObjectEnum::A,
        #[ToObjectsCast(ArrayToObjectSecondChildClass::class)]
        public array $list = [],
    ) {
        //
    }
}


class ArrayToObjectParentClass
{
    use HasMinimalDTO;

    public function __construct(
        public ArrayToObjectFirstChildClass $c,
        public string $a = 'aa',
        public string $b = 'bb',
    ) {
        //
    }
}

class ArrayToObjectFirstChildClass implements ToObjectable
{
    use HasMinimalDTO;

    public function __construct(
        public ArrayToObjectSecondChildClass $f,
        #[PropDefault(1)]
        public int $d,
        public int $e = 1,
    ) {
        //
    }
}

class ArrayToObjectSecondChildClass implements ToObjectable
{
    use HasMinimalDTO;

    public function __construct(
        #[PropDefault(2)]
        public int $g = 1,
        public int $h = 1,
    ) {
        //
    }
}
