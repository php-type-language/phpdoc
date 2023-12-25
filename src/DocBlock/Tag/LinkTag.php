<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\PhpDocParser\DocBlock\Reference\ReferenceInterface;

/**
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/link.html#link
 */
final class LinkTag extends Tag
{
    public function __construct(
        private readonly ReferenceInterface $reference,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct('link', $description);
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     reference: array{
     *         kind: int<0, max>,
     *         ...
     *     },
     *     description?: array{
     *         template: string,
     *         tags: list<array>
     *     }
     * }
     */
    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'reference' => $this->reference->toArray(),
        ];
    }

    public function getReference(): ReferenceInterface
    {
        return $this->reference;
    }
}
