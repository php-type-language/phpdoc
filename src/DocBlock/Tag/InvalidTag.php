<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * This tag is created if a parsing error occurs while parsing the original tag.
 */
final class InvalidTag extends Tag implements InvalidTagInterface
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string $name,
        private readonly \Throwable $reason,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }

    public function getReason(): \Throwable
    {
        return $this->reason;
    }
}
