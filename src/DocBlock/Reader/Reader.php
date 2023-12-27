<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reader;

use JetBrains\PhpStorm\Language;

/**
 * @template TValue of mixed
 * @template-implements ReaderInterface<TValue>
 *
 * @psalm-suppress UndefinedAttributeClass : JetBrains language attribute may not be available
 */
abstract class Reader implements ReaderInterface
{
    /**
     * @var non-empty-string
     */
    private const CHARS_WHITESPACE = " \n\r\t\v\0";

    /**
     * @var non-empty-string
     */
    private const PATTERN_IDENTIFIER = '[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*';

    /**
     * @var non-empty-string
     */
    private const PATTERN_VARIABLE = '\$' . self::PATTERN_IDENTIFIER;

    /**
     * Reads set of characters.
     *
     * @param non-empty-string $chars
     */
    public static function findChars(string $content, string $chars): string
    {
        if ($content === ($suffix = \ltrim($content, $chars))) {
            return '';
        }

        return \substr($content, 0, -\strlen($suffix));
    }

    /**
     * Reads chars while passed set of characters has been found.
     *
     * @param non-empty-string $chars
     */
    public static function findUpToChars(string $content, string $chars): string
    {
        $suffix = \strpbrk($content, $chars);

        if ($content === $suffix || $suffix === false) {
            return '';
        }

        return \substr($content, 0, -\strlen($suffix));
    }

    /**
     * @param non-empty-string $pattern
     */
    public static function findPattern(string $content, #[Language('RegExp')] string $pattern): string
    {
        $pattern = \addcslashes($pattern, '/');

        \preg_match("/^$pattern/isum", $content, $matches);

        return $matches[0] ?? '';
    }

    /**
     * @param non-empty-string $pattern
     */
    public static function findUpToPattern(string $content, #[Language('RegExp')] string $pattern): string
    {
        $pattern = \addcslashes($pattern, '/');

        \preg_match("/^(.+?)$pattern/isum", $content, $matches);

        return $matches[1] ?? '';
    }

    /**
     * @param non-empty-string $sequence
     */
    public static function findSequence(string $content, string $sequence): string
    {
        if (\str_starts_with($content, $sequence)) {
            return $sequence;
        }

        return '';
    }

    /**
     * @param non-empty-string $sequence
     */
    public static function findUpToSequence(string $content, string $sequence): string
    {
        $offset = \strpos($content, $sequence);

        if ($offset) {
            return \substr($content, 0, $offset);
        }

        return '';
    }

    public static function findSpace(string $content): string
    {
        return self::findChars($content, self::CHARS_WHITESPACE);
    }

    public static function findUpToSpace(string $content): string
    {
        return self::findUpToChars($content, self::CHARS_WHITESPACE);
    }

    public static function findIdentifier(string $content): string
    {
        return self::findPattern($content, self::PATTERN_IDENTIFIER);
    }

    public static function findUpToIdentifier(string $content): string
    {
        return self::findPattern($content, self::PATTERN_IDENTIFIER);
    }

    public static function findVariable(string $content): string
    {
        return self::findPattern($content, self::PATTERN_VARIABLE);
    }

    public static function findUpToVariable(string $content): string
    {
        return self::findPattern($content, self::PATTERN_VARIABLE);
    }
}
