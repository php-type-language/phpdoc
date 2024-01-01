<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Reader;

use TypeLang\PhpDoc\Parser\Exception\InvalidVariableNameException;

/**
 * @template-extends Reader<non-empty-string>
 */
final class VariableNameReader extends Reader
{
    /**
     * @var non-empty-string
     */
    private const PATTERN_VAR = '\G\$[a-zA-Z_\x80-\xff][\w\x80-\xff]*';

    /**
     * Read variable name from passed content.
     *
     * Expected argument should be looks like:
     *   - "$var"
     *   - "$var with description"
     *   - "...$var variadic var with description"
     *   - etc...
     *
     * @phpstan-pure
     * @psalm-pure
     *
     * @throws InvalidVariableNameException
     */
    public function read(string $content): Sequence
    {
        if ($content === '') {
            throw InvalidVariableNameException::fromEmptyVariable();
        }

        // Remove starting "..." chars
        if (\str_starts_with($content, '...')) {
            $content = \substr($content, 3);
        }

        if (!\str_starts_with($content, '$')) {
            throw InvalidVariableNameException::fromInvalidVariablePrefix();
        }

        $prefix = self::findPattern($content, self::PATTERN_VAR);

        if ($prefix === '') {
            throw InvalidVariableNameException::fromEmptyVariableName();
        }

        return new Sequence($prefix, \strlen($prefix));
    }
}
