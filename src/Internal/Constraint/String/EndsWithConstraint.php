<?php

declare(strict_types=1);

namespace Klimick\Decode\Internal\Constraint\String;

use Fp\Functional\Either\Either;
use Klimick\Decode\Constraint\ConstraintInterface;
use Klimick\Decode\Context;
use function Klimick\Decode\Constraint\invalid;
use function Klimick\Decode\Constraint\valid;

/**
 * @implements ConstraintInterface<string>
 * @psalm-immutable
 */
final class EndsWithConstraint implements ConstraintInterface
{
    public function __construct(public string $value) { }

    public function name(): string
    {
        return 'ENDS_WITH';
    }

    public function check(Context $context, mixed $value): Either
    {
        return !str_ends_with($value, $this->value)
            ? invalid($context, $this, ['expected' => $this->value])
            : valid();
    }
}
