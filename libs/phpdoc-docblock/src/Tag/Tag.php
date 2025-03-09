<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag;

use TypeLang\PHPDoc\DocBlock\Description\Description;
use TypeLang\PHPDoc\DocBlock\Description\DescriptionInterface;

class Tag implements TagInterface
{
    public readonly ?DescriptionInterface $description;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public readonly string $name,
        \Stringable|string|null $description = null,
    ) {
        $this->description = Description::fromStringableOrNull($description);
    }

    public function __toString(): string
    {
        if ($this->description === null) {
            return \sprintf('@%s', $this->name);
        }

        return \rtrim(\vsprintf('@%s %s', [
            $this->name,
            (string) $this->description,
        ]));
    }
}
