<?php

namespace Test;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use Takemo101\SimpleDTO\Contracts\ToArrayable;
use Takemo101\SimpleDTO\Contracts\ToObjectable;
use Takemo101\SimpleDTO\Contracts\ValueFilterable;
use Takemo101\SimpleDTO\Filters\ValueFilters;
use Takemo101\SimpleDTO\SimpleDTOFacade;
use Takemo101\SimpleDTO\Traits\HasMinimalDTO;

/**
 * ArrayToObject class test
 */
class ValueFilterTest extends TestCase
{
    /**
     * @test
     */
    public function filter__OK()
    {
        $filters = new ValueFilters(
            new IntegerToStringFilter(),
        );

        $this->assertEquals(
            '1',
            $filters->filter(1)
        );
    }

    /**
     * @test
     */
    public function transformObjectPropertiesByFilter__OK()
    {
        SimpleDTOFacade::setFilters(
            new IntegerToStringFilter(),
        );

        $expected = [
            'number' => 12,
        ];

        $object = FilterTestDTO::__fromArray($expected);

        $this->assertTrue(
            $expected['number'] !== $object->__toArray()['number'],
        );

        SimpleDTOFacade::setup();
    }

    /**
     * @test
     */
    public function transformArrayObjectByFilter__OK()
    {
        SimpleDTOFacade::setFilters(
            new ArrayObjectFilter(),
        );

        $object = FilterTestSecondDTO::__fromArray([
            'dto' => [
                'number' => 12,
            ],
        ]);

        $this->assertInstanceOf(
            ArrayObject::class,
            $object->__toArray()['dto'],
        );

        SimpleDTOFacade::setup();

        $this->assertNotInstanceOf(
            ArrayObject::class,
            $object->__toArray()['dto'],
        );
    }
}

class IntegerToStringFilter implements ValueFilterable
{
    public function filter(mixed $value): mixed
    {
        return is_int($value)
            ? (string) $value
            : $value;
    }
}

class ArrayObjectFilter implements ValueFilterable
{
    public function filter(mixed $value): mixed
    {
        return $value instanceof ToArrayable
            ? new ArrayObject($value->__toArray())
            : $value;
    }
}


class FilterTestDTO implements ToObjectable, ToArrayable
{
    use HasMinimalDTO;

    public function __construct(
        public int $number,
    ) {
        //
    }
}

class FilterTestSecondDTO implements ToObjectable
{
    use HasMinimalDTO;

    public function __construct(
        public FilterTestDTO $dto,
    ) {
        //
    }
}
