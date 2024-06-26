<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Factory;

use TypeLang\PHPDoc\Exception\RuntimeExceptionInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Content;
use TypeLang\PHPDoc\Tag\TagInterface;

interface FactoryInterface
{
    /**
     * Returns a tag object with the specified name and description.
     *
     * @param non-empty-string $name
     *
     * @throws RuntimeExceptionInterface in case of parsing error occurs
     * @throws \Throwable in case of internal error occurs
     */
    public function create(string $name, Content $content, DescriptionParserInterface $descriptions): TagInterface;
}
