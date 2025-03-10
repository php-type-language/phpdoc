<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag;

class InvalidTag extends Tag implements InvalidTagInterface
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string $name,
        public readonly \Throwable $reason,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
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
