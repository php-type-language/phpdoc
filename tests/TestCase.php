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

        $baseRules[DescriptionCombinator::NAME] = new \ReflectionClass(DescriptionCombinator::class)
            ->newLazyProxy(function () use (&$tagFactory): DescriptionCombinator {
                if ($tagFactory === null) {
                    return new DescriptionCombinator(new BalancedBraceAwareParser(
                        new StringTagParser(new TagFactory(
                            registry: new TagRegistryBuilder()
                                ->build(),
                            combinators: [
                                UriCombinator::NAME => new UriCombinator(),
                                ReferenceCombinator::NAME => new ReferenceCombinator(),
                                TypeCombinator::NAME => new TypeCombinator(typeParser: new TypeParser()),
                                VariableCombinator::NAME => new VariableCombinator(),
                            ],
                        )),
                    ));
                }

                return new DescriptionCombinator(
                    new BalancedBraceAwareParser(new StringTagParser($tagFactory)),
                );
            });

        $tagFactory = new TagFactory(new TagRegistryBuilder()->build(), $baseRules);

        return $tagFactory;
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
