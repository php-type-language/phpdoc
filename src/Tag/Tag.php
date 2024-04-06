<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\PHPDoc\Tag\Description\Description;
use TypeLang\PHPDoc\Tag\Description\DescriptionInterface;

class Tag implements TagInterface
{
    protected readonly ?DescriptionInterface $description;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        protected readonly string $name,
        \Stringable|string|null $description = null,
    ) {
        $this->description = Description::fromStringableOrNull($description);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?DescriptionInterface
    {
        return $this->description;
    }

    public function jsonSerialize(): array
    {
        return \array_filter([
            'name' => $this->name,
            'description' => $this->description,
        ], static fn(mixed $value): bool => $value !== null);
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
