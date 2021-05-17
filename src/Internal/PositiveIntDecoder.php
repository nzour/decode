<?php

declare(strict_types=1);

namespace Klimick\Decode\Internal;

use Fp\Functional\Either\Either;
use Klimick\Decode\Context;
use Klimick\Decode\DecoderInterface;
use function Klimick\Decode\invalid;
use function Klimick\Decode\valid;

/**
 * @implements DecoderInterface<positive-int>
 * @psalm-immutable
 */
final class PositiveIntDecoder implements DecoderInterface
{
    public function name(): string
    {
        return 'positive-int';
    }

    public function decode(mixed $value, Context $context): Either
    {
        return is_int($value) && $value > 0 ? valid($value) : invalid($context);
    }
}
