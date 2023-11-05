<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Extractor;

use TypeLang\PhpDocParser\Exception\InvalidTagVariableNameException;

final class TagVariableExtractor
{
    /**
     * @psalm-immutable
     * @return array{non-empty-string|null, non-empty-string|null}
     */
    public function extractOrNull(?string $body): array
    {
        if ($body === null) {
            return [null, null];
        }

        if (\str_starts_with($body, '...')) {
            $body = \substr($body, 3);
        }

        \preg_match('/^\$[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*/u', $body, $vars);

        if ($vars === []) {
            return [null, $body ?: null];
        }

        $description = \trim(\substr($body, \strlen($vars[0])));

        /** @var array{non-empty-string, non-empty-string|null} */
        return [$vars[0], $description ?: null];
    }

    /**
     * @psalm-immutable
     * @return array{non-empty-string, non-empty-string|null}
     */
    public function extractOrFail(?string $body): array
    {
        [$variable, $description] = $this->extractOrNull($body);

        if ($variable === null) {
            throw InvalidTagVariableNameException::fromNonTyped();
        }

        return [$variable, $description];
    }
}
