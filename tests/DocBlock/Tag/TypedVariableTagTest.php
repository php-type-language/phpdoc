<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\MagicPropertyTag;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyReadTag;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyReadTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TypedTagInterface;
use TypeLang\PhpDoc\DocBlock\Tag\TypedVariableTag;
use TypeLang\PhpDoc\DocBlock\Tag\VariableTagInterface;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Exception\MalformedTagException;
use TypeLang\PhpDoc\TagFactory;
use TypeLang\PhpDoc\Tests\TestCase;
use TypeLang\Type\NamedTypeNode;

final class TypedVariableTagTest extends TestCase
{
    #[Test]
    public function parsesTypeVariableAndDescription(): void
    {
        $tag = self::factory()->create('param', 'non-empty-list<int> $items The collected items.');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertInstanceOf(TypedTagInterface::class, $tag);
        self::assertInstanceOf(VariableTagInterface::class, $tag);
        self::assertSame('param', $tag->name);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertSame('items', $tag->variable);
        self::assertSame('The collected items.', (string) $tag->description);
        self::assertSame('@param non-empty-list<int> $items The collected items.', (string) $tag);
    }

    #[Test]
    public function parsesTypeAndVariableWithoutDescription(): void
    {
        $tag = self::factory()->create('property-read', 'string $name');

        self::assertInstanceOf(PropertyReadTag::class, $tag);
        self::assertInstanceOf(MagicPropertyTag::class, $tag);
        self::assertSame('name', $tag->variable);
        self::assertNull($tag->description);
        self::assertSame('@property-read string $name', (string) $tag);
    }

    #[Test]
    public function missingRequiredVariableProducesInvalidTag(): void
    {
        $tag = self::factory()->create('param', 'int');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(MalformedTagException::class, $tag->reason);
    }

    /**
     * @return iterable<string, array{string, class-string<TypedVariableTag>}>
     */
    public static function tagProvider(): iterable
    {
        yield '@param' => ['param', ParamTag::class];
        yield '@property-read' => ['property-read', PropertyReadTag::class];
    }

    /**
     * @param class-string<TypedVariableTag> $expected
     */
    #[Test]
    #[DataProvider('tagProvider')]
    public function tagResolvesThroughTheRealParser(string $name, string $expected): void
    {
        $block = new DocBlockParser()->parse(\sprintf('/** @%s int $x */', $name));

        self::assertCount(1, $block->tags);
        self::assertInstanceOf($expected, $block->tags[0]);
        self::assertSame($name, $block->tags[0]->name);
        self::assertSame('x', $block->tags[0]->variable);
    }

    private static function factory(): TagFactory
    {
        return new TagFactory(
            definitions: [
                ParamTagDefinition::NAME => new ParamTagDefinition(),
                PropertyReadTagDefinition::NAME => new PropertyReadTagDefinition(),
            ],
            combinators: [
                TypeCombinator::NAME => new TypeCombinator(new TypeParser()),
                VariableCombinator::NAME => new VariableCombinator(),
                DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
            ],
        );
    }
}
