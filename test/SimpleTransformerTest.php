<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleDTO\Attributes\Getter;
use Takemo101\SimpleDTO\SimpleDTOFacade;
use Takemo101\SimpleDTO\Traits\HasSimpleDTO;
use Takemo101\SimpleDTO\Transformers\SimpleTransformer;

/**
 * SimpleTransformerTest class test
 */
class SimpleTransformerTest extends TestCase
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

        $transformer = new SimpleTransformer();

        $array = $transformer->transformToArray($object->toArray());

        $this->assertEquals('a', $array['a']);
        $this->assertEquals('b', $array['object']['b']);
    }

    /**
     * @test
     */
    public function toArray__NG()
    {
        SimpleDTOFacade::setEmptyTransformers();

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

        SimpleDTOFacade::setDefaultTransformers();
    }

    /**
     * @test
     */
    public function toArray__OK()
    {
        SimpleDTOFacade::setDefaultTransformers();

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
