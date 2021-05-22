<?php

declare(strict_types=1);

namespace Klimick\Decode\Internal;

use Fp\Functional\Either\Either;
use Klimick\Decode\Context;
use Klimick\Decode\Decoder;
use function Klimick\Decode\invalid;
use function Klimick\Decode\valid;

/**
 * @extends Decoder<float>
 * @psalm-immutable
 */
final class FloatDecoder extends Decoder
{
    public function name(): string
    {
        return 'float';
    }

    public function decode(mixed $value, Context $context): Either
    {
        return is_float($value) ? valid($value) : invalid($context);
    }
}
