<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Description\Description;
use TypeLang\PhpDoc\DocBlock\DocBlock;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;
use TypeLang\PhpDoc\Tests\TestCase;

final class DocBlockTest extends TestCase
{
    #[Test]
    public function descriptionDefaultsToNull(): void
    {
        self::assertNull(new DocBlock()->description);
    }

    #[Test]
    public function constructorKeepsExistingDescriptionInstance(): void
    {
        $description = new Description('Summary');

        self::assertSame($description, new DocBlock($description)->description);
    }

    #[Test]
    public function tagsDefaultToEmptyList(): void
    {
        self::assertSame([], new DocBlock()->tags);
    }

    #[Test]
    public function constructorStoresTagsAsList(): void
    {
        $first = new Tag('param');
        $second = new Tag('return');

        $docblock = new DocBlock(null, [3 => $first, 7 => $second]);

        self::assertSame([$first, $second], $docblock->tags);
    }

    #[Test]
    public function constructorAcceptsTraversableTags(): void
    {
        $tag = new Tag('deprecated');

        $docblock = new DocBlock(null, new \ArrayIterator([$tag]));

        self::assertSame([$tag], $docblock->tags);
    }

    #[Test]
    public function countReturnsNumberOfTags(): void
    {
        $docblock = new DocBlock(null, [new Tag('param'), new Tag('return')]);

        self::assertCount(2, $docblock);
    }

    #[Test]
    public function offsetExistsReflectsTagPresence(): void
    {
        $docblock = new DocBlock(null, [new Tag('param')]);

        self::assertTrue(isset($docblock[0]));
        self::assertFalse(isset($docblock[1]));
    }

    #[Test]
    public function offsetGetReturnsTag(): void
    {
        $tag = new Tag('param');
        $docblock = new DocBlock(null, [$tag]);

        self::assertSame($tag, $docblock[0]);
    }

    #[Test]
    public function offsetGetReturnsNullForMissingOffset(): void
    {
        self::assertNull(new DocBlock()[42]);
    }

    #[Test]
    public function offsetSetThrows(): void
    {
        $docblock = new DocBlock();

        $this->expectException(\BadMethodCallException::class);

        $docblock[0] = new Tag('param');
    }

    #[Test]
    public function offsetUnsetThrows(): void
    {
        $docblock = new DocBlock(null, [new Tag('param')]);

        $this->expectException(\BadMethodCallException::class);

        unset($docblock[0]);
    }

    #[Test]
    public function iteratorYieldsAllTagsInOrder(): void
    {
        $tags = [new Tag('param'), new Tag('return')];
        $docblock = new DocBlock(null, $tags);

        self::assertSame($tags, \iterator_to_array($docblock, false));
    }
}
