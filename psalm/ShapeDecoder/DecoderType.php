<?php

declare(strict_types=1);

namespace Klimick\PsalmDecode\ShapeDecoder;

use Psalm\Type;
use Klimick\Decode\DecoderInterface;

final class DecoderType
{
    /**
     * @param array<string, Type\Union> $properties
     */
    public static function createShape(array $properties): Type\Union
    {
        $properties_atomic = new Type\Union([
            empty($properties)
                ? new Type\Atomic\TArray([Type::getEmpty(), Type::getEmpty()])
                : new Type\Atomic\TKeyedArray($properties)
        ]);

        $decoder_atomic = new Type\Atomic\TGenericObject(DecoderInterface::class, [$properties_atomic]);

        return new Type\Union([$decoder_atomic]);
    }

    public static function createObject(string $class): Type\Union
    {
        return new Type\Union([
            new Type\Atomic\TGenericObject(DecoderInterface::class, [
                new Type\Union([
                    new Type\Atomic\TNamedObject($class),
                ])
            ]),
        ]);
    }
}