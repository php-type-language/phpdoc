<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\DocBlockParserInterface;
use TypeLang\PhpDoc\Tests\TestCase;

abstract class TagTestCase extends TestCase
{
    private static ?DocBlockParserInterface $defaultParser = null;

    /**
     * @param non-empty-string $tag
     */
    protected static function parseTag(string $tag): TagInterface
    {
        $tags = self::parseTags('/** ' . $tag . ' */');

        self::assertCount(1, $tags, 'The docblock is expected to contain a single tag');

        return $tags[0];
    }

    /**
     * @return list<TagInterface>
     */
    protected static function parseTags(string $docBlock): array
    {
        return (self::$defaultParser ??= DocBlockParser::create())
            ->parse($docBlock)
            ->tags;
    }
}
