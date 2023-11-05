<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\PhpDocParser\DocBlock\Description;

abstract class Tag implements TagInterface
{
    protected readonly ?Description $description;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        protected readonly string $name,
        Description|string|null $description = null,
    ) {
        if (\is_string($description)) {
            $description = Description::create($description);
        }

        $this->description = $description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): Description|null
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
            (string)$this->description,
        ]);
    }
}
