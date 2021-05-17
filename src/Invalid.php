<?php

declare(strict_types=1);

namespace Klimick\Decode;

/**
 * @psalm-immutable
 */
final class Invalid
{
    /**
     * @param non-empty-list<TypeError> $errors
     */
    public function __construct(
        public array $errors,
    ) { }
}
