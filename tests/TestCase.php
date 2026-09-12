<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase as BaseTestCase;
use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\ReferenceCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\Parser\Description\BalancedBraceAwareParser;
use TypeLang\PhpDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Tag\StringTagParser;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistryBuilder;
use TypeLang\PhpDoc\TagFactoryInterface;

#[Group('type-lang/phpdoc')]
abstract class TestCase extends BaseTestCase
{
    private static ?TagFactoryInterface $cachedTagFactory = null;
    private static ?DescriptionParserInterface $cachedDescriptionParser = null;

    protected static function createTagFactory(): TagFactoryInterface
    {
        return self::$cachedTagFactory ??= self::buildTagFactory();
    }

    private static function buildTagFactory(): TagFactoryInterface
    {
        $typeParser = new TypeParser();

        $baseRules = [
            UriCombinator::NAME => new UriCombinator(),
            ReferenceCombinator::NAME => new ReferenceCombinator(),
            TypeCombinator::NAME => new TypeCombinator(typeParser: $typeParser),
            VariableCombinator::NAME => new VariableCombinator(),
        ];

        $tagFactory = null;
        $description = null;

        $baseRules[DescriptionCombinator::NAME] = static function (Cursor $cursor) use (
            &$tagFactory,
            &$description,
        ): mixed {
            $description ??= new DescriptionCombinator(
                new BalancedBraceAwareParser(new StringTagParser($tagFactory)),
            );

            return $description($cursor);
        };

        return $tagFactory = new TagFactory((new TagRegistryBuilder())->build(), $baseRules);
    }

    protected static function createDescriptionParser(): DescriptionParserInterface
    {
        if (self::$cachedDescriptionParser === null) {
            self::$cachedDescriptionParser = new BalancedBraceAwareParser(
                new StringTagParser(self::createTagFactory()),
            );
        }

        return self::$cachedDescriptionParser;
    }
}
