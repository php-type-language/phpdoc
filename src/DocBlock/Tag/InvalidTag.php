<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag;

final class InvalidTag extends Tag implements InvalidTagInterface
{
    /**
     * @var non-empty-string
     */
    public const DEFAULT_UNKNOWN_TAG_NAME = 'invalid';

    private readonly bool $isUnknownName;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public readonly \Throwable $reason,
        \Stringable|string|null $description = null,
        ?string $name = null,
    ) {
        $this->isUnknownName = $name === null;

        parent::__construct($name ?? self::DEFAULT_UNKNOWN_TAG_NAME, $description);
    }

    public function __toString(): string
    {
        if ($this->isUnknownName) {
            return \sprintf('@%s', $this->description);
        }

        if ($this->description === null) {
            return \sprintf('@%s', $this->name);
        }

        return \rtrim(\vsprintf('@%s %s', [
            $this->name,
            (string) $this->description,
        ]));
    }
}
