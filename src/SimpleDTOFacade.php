<?php

namespace Takemo101\SimpleDTO;

use Takemo101\SimpleDTO\Contracts\DTOTransformer;
use Takemo101\SimpleDTO\Contracts\ValueFilterable;
use Takemo101\SimpleDTO\Filters\ValueFilters;
use Takemo101\SimpleDTO\Transformers\DTOTransformers;
use Takemo101\SimpleDTO\Transformers\SimpleTransformer;

/**
 * simple dto facade
 */
final class SimpleDTOFacade
{
    /**
     * @var DTOTransformers
     */
    private static ?DTOTransformers $transformers = null;

    /**
     * @var ValueConverter
     */
    private static ?ValueConverter $converter = null;

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
        $adapter = $dto instanceof DTOAdapter
            ? $dto
            : new DTOAdapter($dto);

        return self::transformers()->transformToArray(
            $adapter->toArray(self::converter()),
        );
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
        return ArrayToObject::fromArrayToObject(
            $class,
            self::transformers()->transformToObject($data),
        );
    }

    /**
     * Set up the required data
     *
     * @return void
     */
    public static function setup(): void
    {
        self::setDefaultTransformers();
        self::setDefaultFilters();
    }

    /**
     * get transformers instance
     *
     * @return DTOTransformers
     */
    private static function transformers(): DTOTransformers
    {
        $transformers = self::$transformers;

        if (is_null($transformers)) {
            self::setDefaultTransformers();

            /** @var DTOTransformers */
            $transformers = self::$transformers;
        }

        return $transformers;
    }

    /**
     * set transformer
     *
     * @param DTOTransformer ...$transformers
     * @return void
     */
    public static function setTransformers(DTOTransformer ...$transformers): void
    {
        self::$transformers = new DTOTransformers(
            ...$transformers,
        );
    }

    /**
     * set default transformer
     *
     * @return void
     */
    public static function setDefaultTransformers(): void
    {
        self::$transformers = new DTOTransformers(
            new SimpleTransformer(),
        );
    }

    /**
     * set empty transformer
     *
     * @return void
     */
    public static function setEmptyTransformers(): void
    {
        self::$transformers = DTOTransformers::empty();
    }

    /**
     * get value converter instance
     *
     * @return ValueConverter
     */
    public static function converter(): ValueConverter
    {
        $converter = self::$converter;

        if (is_null($converter)) {
            self::setDefaultFilters();

            /** @var ValueConverter */
            $converter = self::$converter;
        }

        return $converter;
    }

    /**
     * set value filters
     *
     * @return void
     */
    public static function setFilters(ValueFilterable ...$filters): void
    {
        self::$converter = new ValueConverter(
            new ValueFilters(...$filters),
        );
    }

    /**
     * set default filters
     *
     * @return void
     */
    public static function setDefaultFilters(): void
    {
        self::$converter = ValueConverter::empty();
    }
}
