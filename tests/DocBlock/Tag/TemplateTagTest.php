<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateContravariantTag;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateCovariantTag;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateTag;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TypeParameterTag;

final class TemplateTagTest extends TagTestCase
{
    #[Test]
    public function parsesNameBoundDefaultAndDescription(): void
    {
        $tag = self::parseTag('@template T of \Countable = array The item type.');

        self::assertInstanceOf(TemplateTag::class, $tag);
        self::assertInstanceOf(TypeParameterTag::class, $tag);
        self::assertSame('template', $tag->name);
        self::assertSame('T', $tag->parameter);
        self::assertNotNull($tag->bound);
        self::assertSame('\Countable', (string) $tag->bound);
        self::assertNotNull($tag->default);
        self::assertSame('array', (string) $tag->default);
        self::assertSame('The item type.', (string) $tag->description);
        self::assertSame('@template T of \Countable = array The item type.', (string) $tag);
    }

    #[Test]
    public function parsesBareName(): void
    {
        $tag = self::parseTag('@template TValue');

        self::assertInstanceOf(TemplateTag::class, $tag);
        self::assertSame('TValue', $tag->parameter);
        self::assertNull($tag->bound);
        self::assertNull($tag->default);
        self::assertNull($tag->description);
        self::assertSame('@template TValue', (string) $tag);
    }

    #[Test]
    public function parsesBoundWithoutDefault(): void
    {
        $tag = self::parseTag('@template T of string');

        self::assertSame('T', $tag->parameter);
        self::assertSame('string', (string) $tag->bound);
        self::assertNull($tag->default);
    }

    #[Test]
    public function keywordRespectsWordBoundary(): void
    {
        $tag = self::parseTag('@template T offset from the base');

        self::assertSame('T', $tag->parameter);
        self::assertNull($tag->bound);
        self::assertSame('offset from the base', (string) $tag->description);
    }

    #[Test]
    public function parsesVarianceTags(): void
    {
        $tags = self::parseTags(<<<'PHPDOC'
            /**
             * @template-covariant T
             * @template-contravariant U of \Throwable
             */
            PHPDOC);

        self::assertInstanceOf(TemplateCovariantTag::class, $tags[0]);
        self::assertSame('template-covariant', $tags[0]->name);
        self::assertSame('T', $tags[0]->parameter);

        self::assertInstanceOf(TemplateContravariantTag::class, $tags[1]);
        self::assertSame('template-contravariant', $tags[1]->name);
        self::assertSame('U', $tags[1]->parameter);
        self::assertSame('\Throwable', (string) $tags[1]->bound);
    }

    /**
     * @param class-string<TypeParameterTag> $expected
     */
    #[Test]
    #[DataProvider('templateTagProvider')]
    public function templateTagIsRecognized(string $name, string $expected): void
    {
        $tag = self::parseTag(\sprintf('@%s T', $name));

        self::assertInstanceOf($expected, $tag);
        self::assertInstanceOf(TypeParameterTag::class, $tag);
        self::assertSame($name, $tag->name);
        self::assertSame('T', $tag->parameter);
    }

    /**
     * @return iterable<string, array{non-empty-string, class-string<TypeParameterTag>}>
     */
    public static function templateTagProvider(): iterable
    {
        yield '@template' => ['template', TemplateTag::class];
        yield '@template-covariant' => ['template-covariant', TemplateCovariantTag::class];
        yield '@template-contravariant' => ['template-contravariant', TemplateContravariantTag::class];
        yield '@template-invariant is an alias of @template' => ['template-invariant', TemplateTag::class];
    }
}
