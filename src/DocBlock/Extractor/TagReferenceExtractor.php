<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Extractor;

use TypeLang\PhpDocParser\DocBlock\Reference\ReferenceInterface;
use TypeLang\PhpDocParser\DocBlock\Reference\UriReference;

final class TagReferenceExtractor
{
    public function extract(string $body): array
    {
        $description = \strpbrk($body, " \t\n\r\0\x0B");

        if ($description === false) {
            return [$this->parseReference($body), null];
        }

        $descriptionOffset = \strlen($body) - \strlen($description);

        return [
            $this->parseReference(\substr($body, 0, $descriptionOffset)),
            \ltrim($description),
        ];
    }

    private function parseReference(string $body): ReferenceInterface
    {
        return new UriReference($body);
    }
}
