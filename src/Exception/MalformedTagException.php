<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Exception;

/**
 * Occurs when a tag is recognized by name, but its body does not match the
 * grammar declared for that tag.
 */
final class MalformedTagException extends InvalidTagException
{
    /**
     * @param string $tag the tag name, without the leading "@"
     * @param string $grammar the expected grammar, e.g. `<URI> [ <description> ]`
     * @param int<0, max> $offset byte offset of the failure within $source
     */
    public static function becauseTagBodyIsMalformed(
        string $tag,
        string $grammar,
        string $source,
        int $offset = 0,
    ): self {
        $message = \sprintf('Malformed "@%s" tag, expected: %s', $tag, $grammar);

        return new self($source, $offset, $message);
    }
}
