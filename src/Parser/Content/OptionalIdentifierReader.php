<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

/**
 * @template-implements OptionalReaderInterface<non-empty-string>
 */
final class OptionalIdentifierReader implements OptionalReaderInterface
{
    public function __invoke(Stream $stream): ?string
    {
        // @phpstan-ignore-next-line : PHPStan false positive
        if ($stream->value === '') {
            return null;
        }

        \preg_match('/([a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\b/u', $stream->value, $matches);

        if (\count($matches) !== 2) {
            return null;
        }

        $stream->shift(\strlen($matches[0]));

        return $matches[1];
    }
}
