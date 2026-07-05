<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Description;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Description\Description;
use TypeLang\PhpDoc\DocBlock\Description\TaggedDescription;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Tests\TestCase;

final class TaggedDescriptionTest extends TestCase
{
    #[Test]
    public function constructorStoresComponentsAsList(): void
    {
        $first = new Description('a');
        $second = new Tag('see');

        $description = new TaggedDescription([5 => $first, 9 => $second]);

        self::assertSame([$first, $second], $description->components);
    }

    #[Test]
    public function componentsDefaultToEmptyList(): void
    {
        self::assertSame([], new TaggedDescription()->components);
    }

    #[Test]
    public function constructorAcceptsTraversable(): void
    {
        $tag = new Tag('see');

        $description = new TaggedDescription(new \ArrayIterator([$tag]));

        self::assertSame([$tag], $description->components);
    }

    #[Test]
    public function tagsContainsOnlyTagComponents(): void
    {
        $tag = new Tag('see');

        $description = new TaggedDescription([
            new Description('text '),
            $tag,
            new Description(' more'),
        ]);

        self::assertSame([$tag], $description->tags);
    }

    #[Test]
    public function tagsIsEmptyWithoutTagComponents(): void
    {
        $description = new TaggedDescription([new Description('only text')]);

        self::assertSame([], $description->tags);
    }

    #[Test]
    public function countReturnsNumberOfComponents(): void
    {
        $description = new TaggedDescription([
            new Description('a'),
            new Tag('see'),
            new Description('b'),
        ]);

        self::assertCount(3, $description);
    }

    #[Test]
    public function offsetExistsReflectsComponentPresence(): void
    {
        $description = new TaggedDescription([new Description('a')]);

        self::assertTrue(isset($description[0]));
        self::assertFalse(isset($description[1]));
    }

    #[Test]
    public function offsetGetReturnsComponent(): void
    {
        $component = new Description('a');
        $description = new TaggedDescription([$component]);

        self::assertSame($component, $description[0]);
    }

    #[Test]
    public function offsetGetReturnsNullForMissingOffset(): void
    {
        self::assertNull(new TaggedDescription()[42]);
    }

    #[Test]
    public function offsetSetThrows(): void
    {
        $description = new TaggedDescription();

        $this->expectException(\BadMethodCallException::class);

        $description[0] = new Description('a');
    }

    #[Test]
    public function offsetUnsetThrows(): void
    {
        $description = new TaggedDescription([new Description('a')]);

        $this->expectException(\BadMethodCallException::class);

        unset($description[0]);
    }

    #[Test]
    public function iteratorYieldsAllComponentsInOrder(): void
    {
        $components = [new Description('a'), new Tag('see'), new Description('b')];
        $description = new TaggedDescription($components);

        self::assertSame($components, \iterator_to_array($description, false));
    }

    #[Test]
    public function tagComponentsAreTagInterfaceInstances(): void
    {
        $description = new TaggedDescription([new Tag('see')]);

        self::assertContainsOnlyInstancesOf(TagInterface::class, $description->tags);
    }
}
