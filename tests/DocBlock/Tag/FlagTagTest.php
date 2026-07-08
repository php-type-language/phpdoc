<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\AbstractTag\AbstractTag;
use TypeLang\PhpDoc\DocBlock\Tag\AbstractTag\AbstractTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AllowPrivateMutationTag\AllowPrivateMutationTag;
use TypeLang\PhpDoc\DocBlock\Tag\ApiTag\ApiTag;
use TypeLang\PhpDoc\DocBlock\Tag\CategoryTag\CategoryTag;
use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreEndTag\CodingStandardsIgnoreEndTag;
use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreFileTag\CodingStandardsIgnoreFileTag;
use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreLineTag\CodingStandardsIgnoreLineTag;
use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreStartTag\CodingStandardsIgnoreStartTag;
use TypeLang\PhpDoc\DocBlock\Tag\ConsistentConstructorTag\ConsistentConstructorTag;
use TypeLang\PhpDoc\DocBlock\Tag\CopyrightTag\CopyrightTag;
use TypeLang\PhpDoc\DocBlock\Tag\FilesourceTag\FilesourceTag;
use TypeLang\PhpDoc\DocBlock\Tag\FinalTag\FinalTag;
use TypeLang\PhpDoc\DocBlock\Tag\FlagTagInterface;
use TypeLang\PhpDoc\DocBlock\Tag\FormatterOffTag\FormatterOffTag;
use TypeLang\PhpDoc\DocBlock\Tag\FormatterOnTag\FormatterOnTag;
use TypeLang\PhpDoc\DocBlock\Tag\IgnoreTag\IgnoreTag;
use TypeLang\PhpDoc\DocBlock\Tag\ImmutableTag\ImmutableTag;
use TypeLang\PhpDoc\DocBlock\Tag\InheritDocTag\InheritDocTag;
use TypeLang\PhpDoc\DocBlock\Tag\InternalTag\InternalTag;
use TypeLang\PhpDoc\DocBlock\Tag\InternalTag\InternalTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\NoNamedArgumentsTag\NoNamedArgumentsTag;
use TypeLang\PhpDoc\DocBlock\Tag\NotDeprecatedTag\NotDeprecatedTag;
use TypeLang\PhpDoc\DocBlock\Tag\OverrideTag\OverrideTag;
use TypeLang\PhpDoc\DocBlock\Tag\PackageTag\PackageTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanConstructorUsedForSideEffectsTag\PhanConstructorUsedForSideEffectsTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanForbidUndeclaredMagicMethodsTag\PhanForbidUndeclaredMagicMethodsTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanForbidUndeclaredMagicPropertiesTag\PhanForbidUndeclaredMagicPropertiesTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanOutputReferenceTag\PhanOutputReferenceTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSideEffectFreeTag\PhanSideEffectFreeTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanTransientTag\PhanTransientTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanWriteOnlyTag\PhanWriteOnlyTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhpStanIgnoreLineTag\PhpStanIgnoreLineTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhpStanIgnoreNextLineTag\PhpStanIgnoreNextLineTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhpStanImpureTag\PhpStanImpureTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmConsistentTemplatesTag\PsalmConsistentTemplatesTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmExternalMutationFreeTag\PsalmExternalMutationFreeTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreFalsableReturnTag\PsalmIgnoreFalsableReturnTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreNullableReturnTag\PsalmIgnoreNullableReturnTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreVariableMethodTag\PsalmIgnoreVariableMethodTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreVariablePropertyTag\PsalmIgnoreVariablePropertyTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreVarTag\PsalmIgnoreVarTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmMutationFreeTag\PsalmMutationFreeTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmNoSealMethodsTag\PsalmNoSealMethodsTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmNoSealPropertiesTag\PsalmNoSealPropertiesTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmOverrideMethodVisibilityTag\PsalmOverrideMethodVisibilityTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmOverridePropertyVisibilityTag\PsalmOverridePropertyVisibilityTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmStubOverrideTag\PsalmStubOverrideTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintSpecializeTag\PsalmTaintSpecializeTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmVariadicTag\PsalmVariadicTag;
use TypeLang\PhpDoc\DocBlock\Tag\PureTag\PureTag;
use TypeLang\PhpDoc\DocBlock\Tag\PureUnlessCallableIsImpureTag\PureUnlessCallableIsImpureTag;
use TypeLang\PhpDoc\DocBlock\Tag\ReadonlyAllowPrivateMutationTag\ReadonlyAllowPrivateMutationTag;
use TypeLang\PhpDoc\DocBlock\Tag\ReadonlyTag\ReadonlyTag;
use TypeLang\PhpDoc\DocBlock\Tag\SealMethodsTag\SealMethodsTag;
use TypeLang\PhpDoc\DocBlock\Tag\SealPropertiesTag\SealPropertiesTag;
use TypeLang\PhpDoc\DocBlock\Tag\StaticTag\StaticTag;
use TypeLang\PhpDoc\DocBlock\Tag\SubpackageTag\SubpackageTag;
use TypeLang\PhpDoc\DocBlock\Tag\TodoTag\TodoTag;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

final class FlagTagTest extends TagTestCase
{
    #[Test]
    public function parsesWithoutDescription(): void
    {
        $tag = self::parseTag('@abstract');

        self::assertInstanceOf(AbstractTag::class, $tag);
        self::assertSame('abstract', $tag->name);
        self::assertNull($tag->description);
        self::assertSame('@abstract', (string) $tag);
    }

    #[Test]
    public function parsesTrailingTextAsDescription(): void
    {
        $tag = self::parseTag('@todo Rewrite this using the new API.');

        self::assertInstanceOf(TodoTag::class, $tag);
        self::assertSame('Rewrite this using the new API.', (string) $tag->description);
        self::assertSame('@todo Rewrite this using the new API.', (string) $tag);
    }

    #[Test]
    public function placementIsRecognized(): void
    {
        self::assertSame(TagPlacement::Any, new InternalTagDefinition()->placement);
        self::assertSame(TagPlacement::Block, new AbstractTagDefinition()->placement);
    }

    /**
     * @param class-string<FlagTagInterface> $expected
     */
    #[Test]
    #[DataProvider('flagTagProvider')]
    public function flagTagIsRecognized(string $name, string $expected): void
    {
        $tag = self::parseTag(\sprintf('@%s', $name));

        self::assertInstanceOf($expected, $tag);
        self::assertInstanceOf(FlagTagInterface::class, $tag);
        self::assertSame($name, $tag->name);
        self::assertNull($tag->description);
        self::assertSame('@' . $name, (string) $tag);
    }

    /**
     * @return iterable<string, array{non-empty-string, class-string<FlagTagInterface>}>
     */
    public static function flagTagProvider(): iterable
    {
        // Standard platform
        yield '@abstract' => ['abstract', AbstractTag::class];
        yield '@api' => ['api', ApiTag::class];
        yield '@final' => ['final', FinalTag::class];
        yield '@internal' => ['internal', InternalTag::class];
        yield '@immutable' => ['immutable', ImmutableTag::class];
        yield '@no-named-arguments' => ['no-named-arguments', NoNamedArgumentsTag::class];
        yield '@not-deprecated' => ['not-deprecated', NotDeprecatedTag::class];
        yield '@override' => ['override', OverrideTag::class];
        yield '@readonly' => ['readonly', ReadonlyTag::class];
        yield '@seal-methods' => ['seal-methods', SealMethodsTag::class];
        yield '@seal-properties' => ['seal-properties', SealPropertiesTag::class];
        yield '@pure-unless-callable-is-impure' => ['pure-unless-callable-is-impure', PureUnlessCallableIsImpureTag::class];

        // phpDocumentor platform
        yield '@static' => ['static', StaticTag::class];
        yield '@todo' => ['todo', TodoTag::class];
        yield '@category' => ['category', CategoryTag::class];
        yield '@copyright' => ['copyright', CopyrightTag::class];
        yield '@filesource' => ['filesource', FilesourceTag::class];
        yield '@ignore' => ['ignore', IgnoreTag::class];
        yield '@inheritDoc' => ['inheritDoc', InheritDocTag::class];
        yield '@package' => ['package', PackageTag::class];
        yield '@subpackage' => ['subpackage', SubpackageTag::class];

        // Shared markers contributed by several tool platforms under their own
        // vendor-prefixed names, each producing the same underlying tag.
        yield '@psalm-pure' => ['psalm-pure', PureTag::class];
        yield '@phpstan-pure' => ['phpstan-pure', PureTag::class];
        yield '@phan-pure' => ['phan-pure', PureTag::class];
        yield '@psalm-allow-private-mutation' => ['psalm-allow-private-mutation', AllowPrivateMutationTag::class];
        yield '@phpstan-allow-private-mutation' => ['phpstan-allow-private-mutation', AllowPrivateMutationTag::class];
        yield '@psalm-consistent-constructor' => ['psalm-consistent-constructor', ConsistentConstructorTag::class];
        yield '@phpstan-consistent-constructor' => ['phpstan-consistent-constructor', ConsistentConstructorTag::class];
        yield '@psalm-readonly-allow-private-mutation' => ['psalm-readonly-allow-private-mutation', ReadonlyAllowPrivateMutationTag::class];
        yield '@phpstan-readonly-allow-private-mutation' => ['phpstan-readonly-allow-private-mutation', ReadonlyAllowPrivateMutationTag::class];

        // Psalm platform markers
        yield '@psalm-consistent-templates' => ['psalm-consistent-templates', PsalmConsistentTemplatesTag::class];
        yield '@psalm-external-mutation-free' => ['psalm-external-mutation-free', PsalmExternalMutationFreeTag::class];
        yield '@psalm-mutation-free' => ['psalm-mutation-free', PsalmMutationFreeTag::class];
        yield '@psalm-ignore-falsable-return' => ['psalm-ignore-falsable-return', PsalmIgnoreFalsableReturnTag::class];
        yield '@psalm-ignore-nullable-return' => ['psalm-ignore-nullable-return', PsalmIgnoreNullableReturnTag::class];
        yield '@psalm-ignore-var' => ['psalm-ignore-var', PsalmIgnoreVarTag::class];
        yield '@psalm-ignore-variable-method' => ['psalm-ignore-variable-method', PsalmIgnoreVariableMethodTag::class];
        yield '@psalm-ignore-variable-property' => ['psalm-ignore-variable-property', PsalmIgnoreVariablePropertyTag::class];
        yield '@psalm-no-seal-methods' => ['psalm-no-seal-methods', PsalmNoSealMethodsTag::class];
        yield '@psalm-no-seal-properties' => ['psalm-no-seal-properties', PsalmNoSealPropertiesTag::class];
        yield '@psalm-override-method-visibility' => ['psalm-override-method-visibility', PsalmOverrideMethodVisibilityTag::class];
        yield '@psalm-override-property-visibility' => ['psalm-override-property-visibility', PsalmOverridePropertyVisibilityTag::class];
        yield '@psalm-stub-override' => ['psalm-stub-override', PsalmStubOverrideTag::class];
        yield '@psalm-taint-specialize' => ['psalm-taint-specialize', PsalmTaintSpecializeTag::class];
        yield '@psalm-variadic' => ['psalm-variadic', PsalmVariadicTag::class];

        // PHPStan platform markers
        yield '@phpstan-impure' => ['phpstan-impure', PhpStanImpureTag::class];
        yield '@phpstan-ignore-line' => ['phpstan-ignore-line', PhpStanIgnoreLineTag::class];
        yield '@phpstan-ignore-next-line' => ['phpstan-ignore-next-line', PhpStanIgnoreNextLineTag::class];

        // Phan platform markers
        yield '@phan-constructor-used-for-side-effects' => ['phan-constructor-used-for-side-effects', PhanConstructorUsedForSideEffectsTag::class];
        yield '@phan-forbid-undeclared-magic-methods' => ['phan-forbid-undeclared-magic-methods', PhanForbidUndeclaredMagicMethodsTag::class];
        yield '@phan-forbid-undeclared-magic-properties' => ['phan-forbid-undeclared-magic-properties', PhanForbidUndeclaredMagicPropertiesTag::class];
        yield '@phan-side-effect-free' => ['phan-side-effect-free', PhanSideEffectFreeTag::class];
        yield '@phan-transient' => ['phan-transient', PhanTransientTag::class];
        yield '@phan-write-only' => ['phan-write-only', PhanWriteOnlyTag::class];
        yield '@phan-output-reference' => ['phan-output-reference', PhanOutputReferenceTag::class];

        // PhpStorm platform markers
        yield '@formatter:off' => ['formatter:off', FormatterOffTag::class];
        yield '@formatter:on' => ['formatter:on', FormatterOnTag::class];

        // PHP CodeSniffer platform markers
        yield '@codingStandardsIgnoreStart' => ['codingStandardsIgnoreStart', CodingStandardsIgnoreStartTag::class];
        yield '@codingStandardsIgnoreEnd' => ['codingStandardsIgnoreEnd', CodingStandardsIgnoreEndTag::class];
        yield '@codingStandardsIgnoreLine' => ['codingStandardsIgnoreLine', CodingStandardsIgnoreLineTag::class];
        yield '@codingStandardsIgnoreFile' => ['codingStandardsIgnoreFile', CodingStandardsIgnoreFileTag::class];
    }
}
