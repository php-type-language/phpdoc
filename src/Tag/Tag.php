<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

class Tag implements \Stringable
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

    /**
     * @return non-empty-string
     *
     * @psalm-immutable
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns description of the tag.
     *
     * @psalm-immutable
     */
    public function getDescription(): ?Description
    {
        return $this->description;
    }

    /**
     * Magic method {@link https://www.php.net/manual/en/language.oop5.magic.php#object.tostring}
     * allows a class to decide how it will react when it is treated like
     * a string.
     *
     * @psalm-immutable
     * @return string Returns string representation of the object that
     *         implements this interface (and/or {@see __toString()} magic
     *         method).
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
