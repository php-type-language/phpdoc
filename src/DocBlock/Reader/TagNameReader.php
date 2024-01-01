<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Reader;

use TypeLang\PhpDoc\Parser\Exception\InvalidTagNameException;

/**
 * @template-extends Reader<non-empty-string>
 */
final class TagNameReader extends Reader
{
    /**
     * @var non-empty-string
     */
    private const PATTERN_TAG = '\G@[a-zA-Z_\x80-\xff][\w\x80-\xff\-:]*';

    /**
     * Read tag name from passed content.
     *
     * Expected argument should be looks like:
     *   - "@tag"
     *   - "@tag with description"
     *   - "@tag With\TypeName"
     *   - "@tag With\TypeName And description"
     *   - "@tag With\TypeName $andVariableName"
     *   - "@tag With\TypeName $andVariableName And description"
     *   - etc...
     *
     * @phpstan-pure
     * @psalm-pure
     *
     * @throws InvalidTagNameException
     */
    public function read(string $content): Sequence
    {
        if ($content === '') {
            throw InvalidTagNameException::fromEmptyTag();
        }

        if (isset($content[0]) && $content[0] !== '@') {
            throw InvalidTagNameException::fromInvalidTagPrefix();
        }

        $prefix = self::findPattern($content, self::PATTERN_TAG);

        if ($prefix === '') {
            throw InvalidTagNameException::fromEmptyTagName();
        }

        return new Sequence($prefix, \strlen($prefix));
    }
}
