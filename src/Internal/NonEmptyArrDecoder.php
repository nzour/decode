<?php

declare(strict_types=1);

namespace Klimick\Decode\Internal;

use Fp\Functional\Either\Either;
use Klimick\Decode\Context;
use Klimick\Decode\Decoder\AbstractDecoder;
use function Klimick\Decode\Decoder\arr;
use function Klimick\Decode\Decoder\invalid;
use function Klimick\Decode\Decoder\valid;

/**
 * @template TKey of array-key
 * @template TVal
 * @extends AbstractDecoder<non-empty-array<TKey, TVal>>
 * @psalm-immutable
 */
final class NonEmptyArrDecoder extends AbstractDecoder
{
    /**
     * @param AbstractDecoder<TKey> $keyDecoder
     * @param AbstractDecoder<TVal> $valDecoder
     */
    public function __construct(
        public AbstractDecoder $keyDecoder,
        public AbstractDecoder $valDecoder,
    ) { }

    public function name(): string
    {
        return "non-empty-array<{$this->keyDecoder->name()}, {$this->valDecoder->name()}>";
    }

    public function decode(mixed $value, Context $context): Either
    {
        return arr($this->keyDecoder, $this->valDecoder)
            ->decode($value, $context)
            ->flatMap(fn($valid) => 0 !== count($valid->value)
                ? valid($valid->value)
                : invalid($context));
    }
}
