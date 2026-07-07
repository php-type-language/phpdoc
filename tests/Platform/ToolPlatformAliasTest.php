<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Platform;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\AbstractTag\AbstractTag;
use TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTag;
use TypeLang\PhpDoc\DocBlock\Tag\ThrowsTag\ThrowsTag;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Tests\TestCase;

final class ToolPlatformAliasTest extends TestCase
{
    #[Test]
    #[DataProvider('psalmProvider')]
    #[DataProvider('phpstanProvider')]
    #[DataProvider('phanProvider')]
    public function aliasResolvesToCanonicalTag(string $alias, string $canonical): void
    {
        $registry = new DocBlockParser()->tags;

        self::assertSame(
            $canonical,
            $registry->get($alias)->name,
            \sprintf('@%s must resolve to @%s', $alias, $canonical),
        );
    }

    #[Test]
    public function typedAliasKeepsItsWrittenName(): void
    {
        $block = new DocBlockParser()->parse('/** @psalm-param int $value */');

        self::assertInstanceOf(ParamTag::class, $block->tags[0]);
        self::assertSame('psalm-param', $block->tags[0]->name);
    }

    #[Test]
    public function flagAliasKeepsItsWrittenName(): void
    {
        $block = new DocBlockParser()->parse('/** @phan-abstract */');

        self::assertInstanceOf(AbstractTag::class, $block->tags[0]);
        self::assertSame('phan-abstract', $block->tags[0]->name);
    }

    #[Test]
    public function typedAliasWithoutPrefixCollisionResolves(): void
    {
        $block = new DocBlockParser()->parse('/** @phpstan-throws \RuntimeException */');

        self::assertInstanceOf(ThrowsTag::class, $block->tags[0]);
        self::assertSame('phpstan-throws', $block->tags[0]->name);
    }

    /**
     * @return iterable<string, array{non-empty-string, non-empty-lowercase-string}>
     */
    public static function psalmProvider(): iterable
    {
        yield '@psalm-api' => ['psalm-api', 'api'];
        yield '@psalm-extends' => ['psalm-extends', 'extends'];
        yield '@psalm-immutable' => ['psalm-immutable', 'immutable'];
        yield '@psalm-implements' => ['psalm-implements', 'implements'];
        yield '@psalm-internal' => ['psalm-internal', 'internal'];
        yield '@psalm-method' => ['psalm-method', 'method'];
        yield '@psalm-param' => ['psalm-param', 'param'];
        yield '@psalm-param-out' => ['psalm-param-out', 'param-out'];
        yield '@psalm-property' => ['psalm-property', 'property'];
        yield '@psalm-property-read' => ['psalm-property-read', 'property-read'];
        yield '@psalm-property-write' => ['psalm-property-write', 'property-write'];
        yield '@psalm-readonly' => ['psalm-readonly', 'readonly'];
        yield '@psalm-require-extends' => ['psalm-require-extends', 'require-extends'];
        yield '@psalm-require-implements' => ['psalm-require-implements', 'require-implements'];
        yield '@psalm-return' => ['psalm-return', 'return'];
        yield '@psalm-seal-methods' => ['psalm-seal-methods', 'seal-methods'];
        yield '@psalm-seal-properties' => ['psalm-seal-properties', 'seal-properties'];
        yield '@psalm-suppress' => ['psalm-suppress', 'suppress'];
        yield '@psalm-template' => ['psalm-template', 'template'];
        yield '@psalm-template-contravariant' => ['psalm-template-contravariant', 'template-contravariant'];
        yield '@psalm-template-covariant' => ['psalm-template-covariant', 'template-covariant'];
        yield '@psalm-use' => ['psalm-use', 'use'];
        yield '@psalm-var' => ['psalm-var', 'var'];
    }

    /**
     * @return iterable<string, array{non-empty-string, non-empty-lowercase-string}>
     */
    public static function phpstanProvider(): iterable
    {
        yield '@phpstan-extends' => ['phpstan-extends', 'extends'];
        yield '@phpstan-immutable' => ['phpstan-immutable', 'immutable'];
        yield '@phpstan-implements' => ['phpstan-implements', 'implements'];
        yield '@phpstan-method' => ['phpstan-method', 'method'];
        yield '@phpstan-param' => ['phpstan-param', 'param'];
        yield '@phpstan-param-closure-this' => ['phpstan-param-closure-this', 'param-closure-this'];
        yield '@phpstan-param-immediately-invoked-callable' => ['phpstan-param-immediately-invoked-callable', 'param-immediately-invoked-callable'];
        yield '@phpstan-param-later-invoked-callable' => ['phpstan-param-later-invoked-callable', 'param-later-invoked-callable'];
        yield '@phpstan-param-out' => ['phpstan-param-out', 'param-out'];
        yield '@phpstan-property' => ['phpstan-property', 'property'];
        yield '@phpstan-property-read' => ['phpstan-property-read', 'property-read'];
        yield '@phpstan-property-write' => ['phpstan-property-write', 'property-write'];
        yield '@phpstan-pure-unless-callable-is-impure' => ['phpstan-pure-unless-callable-is-impure', 'pure-unless-callable-is-impure'];
        yield '@phpstan-readonly' => ['phpstan-readonly', 'readonly'];
        yield '@phpstan-require-extends' => ['phpstan-require-extends', 'require-extends'];
        yield '@phpstan-require-implements' => ['phpstan-require-implements', 'require-implements'];
        yield '@phpstan-return' => ['phpstan-return', 'return'];
        yield '@phpstan-template' => ['phpstan-template', 'template'];
        yield '@phpstan-template-contravariant' => ['phpstan-template-contravariant', 'template-contravariant'];
        yield '@phpstan-template-covariant' => ['phpstan-template-covariant', 'template-covariant'];
        yield '@phpstan-throws' => ['phpstan-throws', 'throws'];
        yield '@phpstan-use' => ['phpstan-use', 'use'];
        yield '@phpstan-var' => ['phpstan-var', 'var'];
    }

    /**
     * @return iterable<string, array{non-empty-string, non-empty-lowercase-string}>
     */
    public static function phanProvider(): iterable
    {
        yield '@phan-abstract' => ['phan-abstract', 'abstract'];
        yield '@phan-extends' => ['phan-extends', 'extends'];
        yield '@phan-immutable' => ['phan-immutable', 'immutable'];
        yield '@phan-inherits' => ['phan-inherits', 'extends'];
        yield '@phan-method' => ['phan-method', 'method'];
        yield '@phan-mixin' => ['phan-mixin', 'mixin'];
        yield '@phan-override' => ['phan-override', 'override'];
        yield '@phan-param' => ['phan-param', 'param'];
        yield '@phan-property' => ['phan-property', 'property'];
        yield '@phan-property-read' => ['phan-property-read', 'property-read'];
        yield '@phan-property-write' => ['phan-property-write', 'property-write'];
        yield '@phan-read-only' => ['phan-read-only', 'readonly'];
        yield '@phan-return' => ['phan-return', 'return'];
        yield '@phan-template' => ['phan-template', 'template'];
        yield '@phan-unused-param' => ['phan-unused-param', 'unused-param'];
        yield '@phan-var' => ['phan-var', 'var'];
    }
}
