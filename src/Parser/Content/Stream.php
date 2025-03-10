<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

use TypeLang\PHPDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PHPDoc\Exception\InvalidTagException;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

final class Stream implements \Stringable
{
    public readonly string $source;

    /**
     * @var int<0, max>
     */
    public int $offset = 0;

    public function __construct(
        /**
         * @var non-empty-string
         */
        public readonly string $tag,
        public string $value,
    ) {
        $this->source = $this->value;
    }

    /**
     * @param int<0, max> $offset
     */
    public function shift(int $offset, bool $ltrim = true): void
    {
        if ($offset <= 0) {
            return;
        }

        $size = \strlen($this->value);
        $this->value = \substr($this->value, $offset);

        if ($ltrim) {
            $this->value = \ltrim($this->value);
        }

        // @phpstan-ignore-next-line : Offset already greater than 0
        $this->offset += $size - \strlen($this->value);
    }

    /**
     * @api
     * @param callable(self):bool $context
     */
    public function lookahead(callable $context): void
    {
        $offset = $this->offset;
        $value = $this->value;

        if ($context($this) === false) {
            $this->offset = $offset;
            $this->value = $value;
        }
    }

    /**
     * @template T of mixed
     *
     * @param callable(Stream):T $applicator
     *
     * @return T
     */
    public function apply(callable $applicator): mixed
    {
        return $applicator($this);
    }

    /**
     * @api
     */
    public function toException(string $message, ?\Throwable $previous = null): InvalidTagException
    {
        return new InvalidTagException(
            source: $this->source,
            offset: $this->offset,
            message: $message,
            previous: $previous,
        );
    }

    /**
     * @api
     */
    public function toDescription(DescriptionParserInterface $descriptions): DescriptionInterface
    {
        return $descriptions->parse($this->value);
    }

    /**
     * @api
     */
    public function toOptionalDescription(DescriptionParserInterface $descriptions): ?DescriptionInterface
    {
        if (\trim($this->value) === '') {
            return null;
        }

        return $descriptions->parse(\rtrim($this->value));
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
