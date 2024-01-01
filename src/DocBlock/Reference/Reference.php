<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Reference;

abstract class Reference implements ReferenceInterface
{
    /**
     * @return array{kind: int<0, max>}
     */
    public function toArray(): array
    {
        return ['kind' => ReferenceKind::UNKNOWN];
    }

    /**
     * @return array{kind: int<0, max>}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
