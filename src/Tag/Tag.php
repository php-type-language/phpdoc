<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\PHPDoc\Tag\Description\Description;

class Tag implements TagInterface
{
    protected readonly ?Description $description;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        protected readonly string $name,
        \Stringable|string|null $description = null,
    ) {
        $this->description = Description::fromStringOrNull($description);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): Description|null
    {
        return $this->description;
    }

    /**
     * @psalm-immutable
     */
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
