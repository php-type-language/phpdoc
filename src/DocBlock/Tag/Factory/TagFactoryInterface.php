<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Factory;

use TypeLang\PHPDoc\DocBlock\Tag\TagInterface;
use TypeLang\PHPDoc\Exception\RuntimeExceptionInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

interface TagFactoryInterface
{
    /**
     * Returns a tag object with the specified name and description.
     *
     * @param non-empty-string $tag
     *
     * @throws RuntimeExceptionInterface in case of parsing error occurs
     * @throws \Throwable in case of internal error occurs
     */
    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): TagInterface;
}
