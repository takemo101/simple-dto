<?php

namespace Takemo101\SimpleDTO;

use Takemo101\SimpleDTO\Contracts\DTOTransformer;
use Takemo101\SimpleDTO\Contracts\ValueFilterable;
use Takemo101\SimpleDTO\Transformers\DTOTransformers;
use Takemo101\SimpleDTO\Transformers\SimpleTransformer;

/**
 * simple dto facade
 */
final class SimpleDTOFacade
{
    /**
     * @var SimpleDTO|null
     */
    private static ?SimpleDTO $instance = null;

    /**
     * private constructor
     */
    private function __construct()
    {
        //
    }

    /**
     * From DTO to array data
     *
     * @param object $dto
     * @return array<string,mixed>
     */
    public static function fromDTOToArray(
        object $dto,
    ): array {
        return self::instance()->fromDTOToArray($dto);
    }

    /**
     * From array data and class name to DTO
     *
     * @param class-string $class
     * @param array<string,mixed> $data
     * @return object
     */
    public static function fromArrayToDTO(
        string $class,
        array $data,
    ): object {
        return self::instance()->fromArrayToDTO($class, $data);
    }

    /**
     * Set up the required data
     *
     * @return SimpleDTO
     */
    public static function instance(): SimpleDTO
    {
        $instance = self::$instance;

        if (is_null($instance)) {
            self::setup();

            /** @var SimpleDTO */
            $instance = self::$instance;
        }

        return $instance;
    }

    /**
     * Set up the required data
     *
     * @param DTOTransformers|null $transformers
     * @param ValueConverter|null $converter
     * @return void
     */
    public static function setup(
        ?DTOTransformers $transformers = null,
        ?ValueConverter $converter = null,
    ): void {
        self::$instance = new SimpleDTO(
            $transformers ?? new DTOTransformers(
                new SimpleTransformer(),
            ),
            $converter ?? ValueConverter::empty(),
        );
    }

    /**
     * Set transformers
     *
     * @param DTOTransformer ...$transformers
     * @return void
     */
    public static function setTransformers(
        DTOTransformer ...$transformers
    ): void {
        self::$instance = self::instance()
            ->setTransformers(...$transformers);
    }

    /**
     * Set filters
     *
     * @param ValueFilterable ...$filters
     * @return void
     */
    public static function setFilters(
        ValueFilterable ...$filters
    ): void {
        self::$instance = self::instance()
            ->setFilters(...$filters);
    }
}
