<?php

namespace Test;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use Takemo101\SimpleDTO\Contracts\ToArrayable;
use Takemo101\SimpleDTO\Contracts\ToObjectable;
use Takemo101\SimpleDTO\Contracts\ValueFilterable;
use Takemo101\SimpleDTO\Filters\CallableFilter;
use Takemo101\SimpleDTO\Filters\ValueFilters;
use Takemo101\SimpleDTO\SimpleDTOFacade;
use Takemo101\SimpleDTO\Traits\HasMinimalDTO;

/**
 * CallableFilter class test
 */
class CallableFilterTest extends TestCase
{
    /**
     * @test
     */
    public function filter__OK()
    {
        $filter = CallableFilter::fromCallable(
            fn (mixed $value) => $value . '_test',
        );

        $this->assertEquals(
            'hello_test',
            $filter->filter('hello')
        );
    }
}
