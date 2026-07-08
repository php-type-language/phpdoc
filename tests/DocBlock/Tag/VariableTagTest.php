<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamImmediatelyInvokedCallableTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamImmediatelyInvokedCallableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamInvokedCallableTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamLaterInvokedCallableTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamLaterInvokedCallableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmAssertUntaintedTag\PsalmAssertUntaintedTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmTraceTag\PsalmTraceTag;
use TypeLang\PhpDoc\DocBlock\Tag\UnusedParamTag\UnusedParamTag;
use TypeLang\PhpDoc\DocBlock\Tag\UnusedParamTag\UnusedParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\VariableTagInterface;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Exception\MalformedTagException;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Tests\TestCase;

final class VariableTagTest extends TestCase
{
    #[Test]
    public function parsesVariableWithDescription(): void
    {
        $tag = self::factory()->create('param-immediately-invoked-callable', '$callback Runs during the call.');

        self::assertInstanceOf(ParamImmediatelyInvokedCallableTag::class, $tag);
        self::assertInstanceOf(VariableTagInterface::class, $tag);
        self::assertSame('param-immediately-invoked-callable', $tag->name);
        self::assertSame('callback', $tag->variable);
        self::assertSame('Runs during the call.', (string) $tag->description);
        self::assertSame('@param-immediately-invoked-callable $callback Runs during the call.', (string) $tag);
    }

    #[Test]
    public function parsesVariableWithoutDescription(): void
    {
        $tag = self::factory()->create('unused-param', '$context');

        self::assertInstanceOf(UnusedParamTag::class, $tag);
        self::assertSame('context', $tag->variable);
        self::assertNull($tag->description);
        self::assertSame('@unused-param $context', (string) $tag);
    }

    #[Test]
    public function invokedCallableTagsShareACommonBase(): void
    {
        $immediately = self::factory()->create('param-immediately-invoked-callable', '$a');
        $later = self::factory()->create('param-later-invoked-callable', '$b');

        self::assertInstanceOf(ParamInvokedCallableTag::class, $immediately);
        self::assertInstanceOf(ParamInvokedCallableTag::class, $later);
        self::assertInstanceOf(ParamLaterInvokedCallableTag::class, $later);
    }

    #[Test]
    public function missingRequiredVariableProducesInvalidTag(): void
    {
        $tag = self::factory()->create('unused-param', '');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(MalformedTagException::class, $tag->reason);
    }

    /**
     * @return iterable<string, array{string, class-string<VariableTagInterface>}>
     */
    public static function tagProvider(): iterable
    {
        yield '@param-immediately-invoked-callable' => [
            'param-immediately-invoked-callable',
            ParamImmediatelyInvokedCallableTag::class,
        ];
        yield '@param-later-invoked-callable' => [
            'param-later-invoked-callable',
            ParamLaterInvokedCallableTag::class,
        ];
        yield '@unused-param' => ['unused-param', UnusedParamTag::class];
        yield '@psalm-assert-untainted' => ['psalm-assert-untainted', PsalmAssertUntaintedTag::class];
        yield '@psalm-trace' => ['psalm-trace', PsalmTraceTag::class];
    }

    /**
     * @param class-string<VariableTagInterface> $expected
     */
    #[Test]
    #[DataProvider('tagProvider')]
    public function tagResolvesThroughTheRealParser(string $name, string $expected): void
    {
        $block = new DocBlockParser()->parse(\sprintf('/** @%s $x */', $name));

        self::assertCount(1, $block->tags);
        self::assertInstanceOf($expected, $block->tags[0]);
        self::assertSame($name, $block->tags[0]->name);
        self::assertSame('x', $block->tags[0]->variable);
    }

    private static function factory(): TagFactory
    {
        $registry = new TagRegistry([
            ParamImmediatelyInvokedCallableTagDefinition::NAME => new ParamImmediatelyInvokedCallableTagDefinition(),
            ParamLaterInvokedCallableTagDefinition::NAME => new ParamLaterInvokedCallableTagDefinition(),
            UnusedParamTagDefinition::NAME => new UnusedParamTagDefinition(),
        ]);

        return new TagFactory($registry, [
            VariableCombinator::NAME => new VariableCombinator(),
            DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
        ]);
    }
}
