<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\ReferenceCombinator;
use TypeLang\PhpDoc\DocBlock\Reference\ClassMethodReference;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\ReferencedTagInterface;
use TypeLang\PhpDoc\DocBlock\Tag\UsedByTag\UsedByTag;
use TypeLang\PhpDoc\DocBlock\Tag\UsedByTag\UsedByTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\UsesTag\UsesTag;
use TypeLang\PhpDoc\DocBlock\Tag\UsesTag\UsesTagDefinition;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Exception\MalformedTagException;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Tests\TestCase;

final class ReferenceTagTest extends TestCase
{
    #[Test]
    public function parsesCodeReferenceWithDescription(): void
    {
        $tag = self::factory()->create('uses', 'Mailer::send() The underlying transport.');

        self::assertInstanceOf(UsesTag::class, $tag);
        self::assertInstanceOf(ReferencedTagInterface::class, $tag);
        self::assertSame('uses', $tag->name);
        self::assertInstanceOf(ClassMethodReference::class, $tag->reference);
        self::assertSame('The underlying transport.', (string) $tag->description);
        self::assertSame('@uses Mailer::send() The underlying transport.', (string) $tag);
    }

    #[Test]
    public function usedByResolvesThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse('/** @used-by Service::run() */');

        self::assertCount(1, $block->tags);
        self::assertInstanceOf(UsedByTag::class, $block->tags[0]);
        self::assertSame('used-by', $block->tags[0]->name);
    }

    /**
     * Unlike "@see", the "@uses" tag references a code element only, so a URI
     * is not a valid target.
     */
    #[Test]
    public function uriIsNotAccepted(): void
    {
        $tag = self::factory()->create('uses', 'https://example.com');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(MalformedTagException::class, $tag->reason);
    }

    private static function factory(): TagFactory
    {
        $registry = new TagRegistry([
            UsesTagDefinition::NAME => new UsesTagDefinition(),
            UsedByTagDefinition::NAME => new UsedByTagDefinition(),
        ]);

        return new TagFactory($registry, [
            ReferenceCombinator::NAME => new ReferenceCombinator(),
            DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
        ]);
    }
}
