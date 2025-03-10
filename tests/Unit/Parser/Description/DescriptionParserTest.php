<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Unit\Parser\Description;

use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Parser\Description\RegexDescriptionParser;
use TypeLang\PHPDoc\Parser\Tag\TagParserInterface;

final class DescriptionParserTest extends DescriptionParserTestCase
{
    public static function getDescriptionParser(TagParserInterface $tags): DescriptionParserInterface
    {
        return new RegexDescriptionParser($tags);
    }
}
