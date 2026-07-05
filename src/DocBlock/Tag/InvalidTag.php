<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;

final class InvalidTag extends Tag
{
    public function __construct(
        public readonly \Throwable $reason,
        string $name = '',
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
