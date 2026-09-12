<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Description;

final class Description implements DescriptionInterface
{
    public function __construct(
        public readonly string $value = '',
    ) {}

    public static function createIfNotEmpty(string $value): ?self
    {
        if ($value === '') {
            return null;
        }

        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
