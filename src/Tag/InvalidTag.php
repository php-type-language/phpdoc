<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

final class InvalidTag extends Tag implements InvalidTagInterface
{
    /**
     * @var non-empty-string
     */
    public const DEFAULT_UNKNOWN_TAG_NAME = '<unknown>';

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        protected readonly \Throwable $reason,
        \Stringable|string|null $description = null,
        string $name = self::DEFAULT_UNKNOWN_TAG_NAME,
    ) {
        parent::__construct($name, $description);
    }

    public function getReason(): \Throwable
    {
        return $this->reason;
    }

    public function __toString(): string
    {
        $name = $this->name === self::DEFAULT_UNKNOWN_TAG_NAME ? '' : $this->name;

        if ($this->description === null) {
            return \sprintf('@%s', $name);
        }

        return \rtrim(\vsprintf('@%s %s', [
            $name,
            (string) $this->description,
        ]));
    }
}
