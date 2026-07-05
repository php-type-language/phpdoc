<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;

class Tag implements TagInterface
{
    public function __construct(
        public readonly string $name = '',
        public readonly ?DescriptionInterface $description = null,
    ) {}

    public function __toString(): string
    {
        $result = \sprintf('@%s', $this->name);

        if ($this->description !== null) {
            if ($this->name !== '') {
                $result .= ' ';
            }

            $result .= $this->description;
        }

        return $result;
    }
}
