<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock\Tag;

use TypeLang\Reader\DocBlock\Description;

abstract class Tag implements TagInterface
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        protected readonly string $name,
        protected readonly Description|string|null $description = null,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): Description|string|null
    {
        return $this->description;
    }

    public function __toString(): string
    {
        if ($this->description === null) {
            return \sprintf('@%s', $this->name);
        }

        return \vsprintf('@%s %s', [
            $this->name,
            $this->description,
        ]);
    }
}
