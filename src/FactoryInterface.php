<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

use TypeLang\PHPDoc\Exception\RuntimeExceptionInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Tag;

interface FactoryInterface
{
    /**
     * Returns a tag object with the specified name and description.
     *
     * @param non-empty-string $name
     *
     * @throws RuntimeExceptionInterface In case of parsing error occurs.
     * @throws \Throwable In case of internal error occurs.
     */
    public function create(string $name, string $content, DescriptionParserInterface $descriptions): Tag;
}
