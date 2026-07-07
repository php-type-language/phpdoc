<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Description\Description;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Tests\TestCase;

final class TagTest extends TestCase
{
    #[Test]
    public function constructorStoresName(): void
    {
        self::assertSame('param', new Tag('param')->name);
    }

    #[Test]
    public function descriptionDefaultsToNull(): void
    {
        self::assertNull(new Tag('param')->description);
    }

    #[Test]
    public function constructorKeepsExistingDescriptionInstance(): void
    {
        $description = new Description('int $a');

        self::assertSame($description, new Tag('param', $description)->description);
    }

    #[Test]
    public function implementsTagInterface(): void
    {
        self::assertInstanceOf(TagInterface::class, new Tag('param'));
    }
}
