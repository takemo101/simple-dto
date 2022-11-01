<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleDTO\Attributes\Getter;
use Takemo101\SimpleDTO\SimpleDTOFacade;
use Takemo101\SimpleDTO\ToArraySimpleTransformer;
use Takemo101\SimpleDTO\Traits\HasSimpleDTO;

/**
 * ToArraySimpleTransformer class test
 */
class ToArraySimpleTransformerTest extends TestCase
{
    /**
     * @test
     */
    public function transform__OK()
    {
        $object = new class(
            'a',
        )
        {
            use HasSimpleDTO;

            public function __construct(
                public string $a,
            ) {
                //
            }

            #[Getter]
            public function object(): object
            {
                return new class(
                    'b',
                )
                {
                    use HasSimpleDTO;

                    public function __construct(
                        public string $b,
                    ) {
                        //
                    }
                };
            }
        };

        $transformer = new ToArraySimpleTransformer();

        $array = $transformer->transform($object->toArray());

        $this->assertEquals('a', $array['a']);
        $this->assertEquals('b', $array['object']['b']);
    }

    /**
     * @test
     */
    public function toArray__NG()
    {
        $object = new class(
            'a',
        )
        {
            use HasSimpleDTO;

            public function __construct(
                public string $a,
            ) {
                //
            }

            #[Getter]
            public function object(): object
            {
                return new class(
                    'b',
                )
                {
                    use HasSimpleDTO;

                    public function __construct(
                        public string $b,
                    ) {
                        //
                    }
                };
            }
        };

        $array = $object->toArray();

        $this->assertEquals('a', $array['a']);
        $this->assertEquals('b', $array['object']->b);
    }

    /**
     * @test
     */
    public function toArray__OK()
    {
        SimpleDTOFacade::setup();

        $object = new class(
            'a',
        )
        {
            use HasSimpleDTO;

            public function __construct(
                public string $a,
            ) {
                //
            }

            #[Getter]
            public function object(): object
            {
                return new class(
                    'b',
                )
                {
                    use HasSimpleDTO;

                    public function __construct(
                        public string $b,
                    ) {
                        //
                    }

                    #[Getter]
                    public function object(): object
                    {
                        return new class(
                            'c',
                        )
                        {
                            use HasSimpleDTO;

                            public function __construct(
                                public string $c,
                            ) {
                                //
                            }
                        };
                    }
                };
            }
        };

        $array = $object->toArray();

        $this->assertEquals('a', $array['a']);
        $this->assertEquals('b', $array['object']['b']);
        $this->assertEquals('c', $array['object']['object']['c']);
    }
}
