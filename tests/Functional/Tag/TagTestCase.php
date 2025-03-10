<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Functional\Tag;

use TypeLang\PHPDoc\DocBlock\Tag\TagInterface;
use TypeLang\PHPDoc\Parser;
use TypeLang\PHPDoc\Tests\Unit\TestCase;

abstract class TagTestCase extends TestCase
{
    protected function parseTag(string $docblock): TagInterface
    {
        $parser = new Parser();

        foreach ($parser->parse($docblock) as $tag) {
            return $tag;
        }

        self::fail(\sprintf('The "%s" docblock does not contain any tags', $docblock));
    }
}
