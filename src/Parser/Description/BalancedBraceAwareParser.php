<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Description;

use TypeLang\PhpDoc\DocBlock\ComponentInterface;
use TypeLang\PhpDoc\DocBlock\Description\Description;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Description\TaggedDescription;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\Exception\InvalidTagNameException;
use TypeLang\PhpDoc\Exception\ParsingExceptionInterface;
use TypeLang\PhpDoc\Parser\Tag\TagParserInterface;

/**
 * A description parser that extracts inline tags using a brace-aware scanner.
 *
 * An inline tag is any `{@...}` sequence whose curly braces are balanced.
 */
final readonly class BalancedBraceAwareParser implements DescriptionParserInterface
{
    private const string INLINE_TAG_START_SEQUENCE = '{@';

    private const string NESTING_INC_CHAR = '{';
    private const string NESTING_DEC_CHAR = '}';

    private const string NESTING_CHARS
        = self::NESTING_INC_CHAR
        . self::NESTING_DEC_CHAR;

    public function __construct(
        private TagParserInterface $tagParser,
    ) {}

    public function tryParse(string $description): ?DescriptionInterface
    {
        if ($description === '') {
            return null;
        }

        return $this->parse($description);
    }

    public function parse(string $description): DescriptionInterface
    {
        $components = $this->getComponents($description);

        return match (\count($components)) {
            0 => new Description(),
            1 => $components[0] instanceof Description
                ? $components[0]
                : new TaggedDescription($components),
            default => new TaggedDescription($components),
        };
    }

    /**
     * Splits the description into an ordered list of raw-text descriptions and
     * parsed inline tags.
     *
     * @return list<ComponentInterface>
     * @throws ParsingExceptionInterface
     */
    private function getComponents(string $description): array
    {
        $components = [];
        $length = \strlen($description);

        // Start of the pending raw-text run that has not been flushed yet.
        $offset = 0;

        // Position from which to look for the next inline tag opening.
        $cursor = 0;

        while (($open = \strpos($description, self::INLINE_TAG_START_SEQUENCE, $cursor)) !== false) {
            $close = $this->findClosingBrace($description, $open, $length);

            // An unclosed "{@..." consumes everything up to the end of the
            // string as a raw description, so the scanning stops here.
            if ($close === null) {
                break;
            }

            // Strip the outer braces: "{@see X}" becomes "@see X".
            $definition = \substr($description, $open + 1, $close - $open - 1);
            // TODO Add an internal exception handling
            $tag = $this->tagParser->parse($definition);

            // A "{@...}" with an unreadable tag name is not a tag at all: keep
            // the original (braced) text as a part of the description by
            // leaving the pending text run untouched and skipping past the
            // closing brace.
            if ($tag instanceof InvalidTag && $tag->reason instanceof InvalidTagNameException) {
                $cursor = $close + 1;

                continue;
            }

            // Flush the raw text accumulated before the tag.
            if ($open > $offset) {
                $components[] = new Description(\substr($description, $offset, $open - $offset));
            }

            $components[] = $tag;
            $offset = $cursor = $close + 1;
        }

        // Flush the trailing text; this also captures an unclosed "{@..." tail.
        if ($offset < $length) {
            $components[] = new Description(\substr($description, $offset));
        }

        return $components;
    }

    /**
     * Returns the offset of the "}" that closes the brace opened at $open,
     * counting every nested "{" and "}" pair, or {@see null} when the braces
     * never balance out before the end of the string.
     *
     * @param int<0, max> $open
     * @param int<0, max> $length
     * @return int<0, max>|null
     */
    private function findClosingBrace(string $description, int $open, int $length): ?int
    {
        $depth = 1;
        $offset = $open + 1;

        while (true) {
            $offset += \strcspn($description, self::NESTING_CHARS, $offset);

            if ($offset >= $length) {
                return null;
            }

            if ($description[$offset] === self::NESTING_INC_CHAR) {
                ++$depth;
            } elseif (--$depth === 0) {
                return $offset;
            }

            ++$offset;
        }
    }
}
