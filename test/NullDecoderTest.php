<?php

declare(strict_types=1);

namespace Klimick\Decode\Test;

use Fp\Functional\Either\Right;
use Klimick\Decode\Test\Helper\Check;
use Klimick\Decode\Test\Helper\Gen;
use PHPUnit\Framework\TestCase;
use function Klimick\Decode\Decoder\decode;
use function Klimick\Decode\Decoder\null;
use function Klimick\Decode\Test\Helper\forAll;
use function PHPUnit\Framework\assertInstanceOf;

final class NullDecoderTest extends TestCase
{
    public function testValidOnlyForNullValue(): void
    {
        assertInstanceOf(Right::class, decode(null, null()));
    }

    public function testInvalidForAllNotNullValue(): void
    {
        forAll(Gen::mixed())
            ->when(fn(mixed $value) => null !== $value)
            ->then(Check::thatInvalidFor(null()));
    }
}
