<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Content;

/**
 * @template-extends Reader<non-empty-string|null>
 */
final class OptionalVariableNameReader extends Reader
{
    /**
     * @return non-empty-string|null
     */
    public function __invoke(Stream $stream): ?string
    {
        if (!\str_starts_with($stream->value, '$')) {
            return null;
        }

        \preg_match('/\$([a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\b/u', $stream->value, $matches);

        if (\count($matches) !== 2) {
            return null;
        }

        $stream->shift(\strlen($matches[0]));

        return $matches[1];
    }
}
