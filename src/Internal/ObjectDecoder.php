<?php

declare(strict_types=1);

namespace Klimick\Decode\Internal;

use Fp\Functional\Either\Either;
use Klimick\Decode\Valid;
use Klimick\Decode\Context;
use Klimick\Decode\Decoder;
use Klimick\Decode\Internal\Shape\ShapeDecoder;
use function Klimick\Decode\valid;

/**
 * @template T of object
 * @extends Decoder<T>
 * @psalm-immutable
 */
final class ObjectDecoder extends Decoder
{
    public ShapeDecoder $shape;

    /**
     * @param class-string<T> $objectClass
     * @param array<array-key, Decoder<mixed>> $decoders
     */
    public function __construct(
        public string $objectClass,
        public array $decoders,
        public bool $partial = false,
    )
    {
        $this->shape = new ShapeDecoder($decoders, $partial);
    }

    public function name(): string
    {
        return $this->objectClass;
    }

    public function decode(mixed $value, Context $context): Either
    {
        return $this->shape
            ->decode($value, $context)
            ->flatMap(function(Valid $valid) {
                /** @psalm-suppress MixedMethodCall */
                $instance = new ($this->objectClass)(...$valid->value);

                return valid($instance);
            });
    }
}
