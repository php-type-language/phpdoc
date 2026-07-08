<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\Tag\ExpectedExceptionTag\ExpectedExceptionTag;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ExtendsTag;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ExtendsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\InheritanceTag;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\MixinTag\MixinTag;
use TypeLang\PhpDoc\DocBlock\Tag\MixinTag\MixinTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanClosureScopeTag\PhanClosureScopeTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanHardcodeReturnTypeTag\PhanHardcodeReturnTypeTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanRealReturnTag\PhanRealReturnTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmIfThisIsTag\PsalmIfThisIsTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmInheritorsTag\PsalmInheritorsTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmScopeThisTag\PsalmScopeThisTag;
use TypeLang\PhpDoc\DocBlock\Tag\ReturnTag\ReturnTag;
use TypeLang\PhpDoc\DocBlock\Tag\ReturnTag\ReturnTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SelfOutTag\SelfOutTag;
use TypeLang\PhpDoc\DocBlock\Tag\ThrowsTag\ThrowsTag;
use TypeLang\PhpDoc\DocBlock\Tag\ThrowsTag\ThrowsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TypedTagInterface;
use TypeLang\PhpDoc\DocBlock\Tag\YieldTag\YieldTag;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\Exception\MalformedTagException;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Tests\TestCase;
use TypeLang\Type\NamedTypeNode;

final class TypedTagTest extends TestCase
{
    #[Test]
    public function parsesTypeWithDescription(): void
    {
        $tag = self::factory()->create('return', 'int<0, max> The number of items.');

        self::assertInstanceOf(ReturnTag::class, $tag);
        self::assertInstanceOf(TypedTagInterface::class, $tag);
        self::assertSame('return', $tag->name);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertSame('int', (string) $tag->type->name);
        self::assertSame('The number of items.', (string) $tag->description);
        self::assertSame('@return int<0, max> The number of items.', (string) $tag);
    }

    #[Test]
    public function parsesTypeWithoutDescription(): void
    {
        $tag = self::factory()->create('throws', '\RuntimeException');

        self::assertInstanceOf(ThrowsTag::class, $tag);
        self::assertNull($tag->description);
        self::assertSame('@throws \RuntimeException', (string) $tag);
    }

    #[Test]
    public function preservesComplexTypeSpelling(): void
    {
        $tag = self::factory()->create('mixin', 'array{id: int, name: string} rest');

        self::assertInstanceOf(MixinTag::class, $tag);
        self::assertSame('@mixin array{id: int, name: string} rest', (string) $tag);
    }

    #[Test]
    public function missingRequiredTypeProducesInvalidTag(): void
    {
        $tag = self::factory()->create('return', '');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(MalformedTagException::class, $tag->reason);
    }

    #[Test]
    public function nameComesFromTheParsedName(): void
    {
        $statement = new TypeReference(new TypeParser()->parse('bool'), 'bool');

        $tag = new ReturnTagDefinition()
            ->create('returns', new TagPayload(['type' => [$statement]]));

        self::assertInstanceOf(ReturnTag::class, $tag);
        self::assertSame('returns', $tag->name);
    }

    #[Test]
    public function inheritanceTagsShareACommonBase(): void
    {
        $tag = self::factory()->create('extends', 'Collection<int, string>');

        self::assertInstanceOf(ExtendsTag::class, $tag);
        self::assertInstanceOf(InheritanceTag::class, $tag);
        self::assertInstanceOf(TypedTagInterface::class, $tag);
        self::assertSame('extends', $tag->name);
        self::assertSame('@extends Collection<int, string>', (string) $tag);
    }

    /**
     * @return iterable<string, array{string, class-string<TypedTagInterface>}>
     */
    public static function tagProvider(): iterable
    {
        yield '@return' => ['return', ReturnTag::class];
        yield '@throws' => ['throws', ThrowsTag::class];
        yield '@mixin' => ['mixin', MixinTag::class];
        yield '@extends' => ['extends', ExtendsTag::class];

        yield '@psalm-if-this-is' => ['psalm-if-this-is', PsalmIfThisIsTag::class];
        yield '@psalm-inheritors' => ['psalm-inheritors', PsalmInheritorsTag::class];
        yield '@psalm-scope-this' => ['psalm-scope-this', PsalmScopeThisTag::class];
        yield '@phan-closure-scope' => ['phan-closure-scope', PhanClosureScopeTag::class];
        yield '@phan-hardcode-return-type' => ['phan-hardcode-return-type', PhanHardcodeReturnTypeTag::class];
        yield '@phan-real-return' => ['phan-real-return', PhanRealReturnTag::class];
        yield '@expectedException' => ['expectedException', ExpectedExceptionTag::class];

        // Shared type tags contributed by several tool platforms under their
        // own vendor-prefixed names, each producing the same underlying tag.
        yield '@psalm-self-out' => ['psalm-self-out', SelfOutTag::class];
        yield '@phpstan-self-out' => ['phpstan-self-out', SelfOutTag::class];
        yield '@psalm-yield' => ['psalm-yield', YieldTag::class];
        yield '@phpstan-yield' => ['phpstan-yield', YieldTag::class];

        // Documented aliases resolve to the same tag class, keeping the name
        // they were written with.
        yield '@returns is an alias of @return' => ['returns', ReturnTag::class];
        yield '@throw is an alias of @throws' => ['throw', ThrowsTag::class];
        yield '@inherits is an alias of @extends' => ['inherits', ExtendsTag::class];
        yield '@template-extends is an alias of @extends' => ['template-extends', ExtendsTag::class];
    }

    /**
     * @param class-string<TypedTagInterface> $expected
     */
    #[Test]
    #[DataProvider('tagProvider')]
    public function tagResolvesThroughTheRealParser(string $name, string $expected): void
    {
        $block = new \TypeLang\PhpDoc\DocBlockParser()
            ->parse(\sprintf('/** @%s Some\\Type */', $name));

        self::assertCount(1, $block->tags);
        self::assertInstanceOf($expected, $block->tags[0]);
        self::assertSame($name, $block->tags[0]->name);
    }

    private static function factory(): TagFactory
    {
        $registry = new TagRegistry([
            ReturnTagDefinition::NAME => new ReturnTagDefinition(),
            ThrowsTagDefinition::NAME => new ThrowsTagDefinition(),
            MixinTagDefinition::NAME => new MixinTagDefinition(),
            ExtendsTagDefinition::NAME => new ExtendsTagDefinition(),
        ]);

        return new TagFactory($registry, [
            TypeCombinator::NAME => new TypeCombinator(new TypeParser()),
            DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
        ]);
    }
}
