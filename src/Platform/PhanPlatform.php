<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\Tag\AbstractTag\AbstractTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AssertIfFalseTag\AssertIfFalseTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AssertIfTrueTag\AssertIfTrueTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AssertTag\AssertTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ImmutableTag\ImmutableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ExtendsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\MethodTag\MethodTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\MixinTag\MixinTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\OverrideTag\OverrideTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanClosureScopeTag\PhanClosureScopeTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanConstructorUsedForSideEffectsTag\PhanConstructorUsedForSideEffectsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanFileSuppressTag\PhanFileSuppressTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanForbidUndeclaredMagicMethodsTag\PhanForbidUndeclaredMagicMethodsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanForbidUndeclaredMagicPropertiesTag\PhanForbidUndeclaredMagicPropertiesTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanHardcodeReturnTypeTag\PhanHardcodeReturnTypeTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanOutputReferenceTag\PhanOutputReferenceTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanRealReturnTag\PhanRealReturnTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSideEffectFreeTag\PhanSideEffectFreeTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressCurrentLineTag\PhanSuppressCurrentLineTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressNextLineTag\PhanSuppressNextLineTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressNextNextLineTag\PhanSuppressNextNextLineTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressPreviousLineTag\PhanSuppressPreviousLineTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanTransientTag\PhanTransientTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhanWriteOnlyTag\PhanWriteOnlyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyReadTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyWriteTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PureTag\PureTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReadonlyTag\ReadonlyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReturnTag\ReturnTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\UnusedParamTag\UnusedParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\VarTag\VarTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;

/**
 * The Phan platform: the `@phan-*` tag family understood by Phan.
 *
 * Tags that restate an existing one are wired as aliases onto it; Phan's own
 * marker tags are contributed as their own flag definitions.
 */
final class PhanPlatform extends Platform
{
    /**
     * @var non-empty-string
     */
    public const string NAME = 'Phan';

    public private(set) string $name = self::NAME;

    /**
     * @var iterable<non-empty-lowercase-string, TagDefinitionInterface>
     */
    public iterable $tags {
        get => [
            'phan-assert' => new AssertTagDefinition(),
            'phan-assert-if-true' => new AssertIfTrueTagDefinition(),
            'phan-assert-if-false' => new AssertIfFalseTagDefinition(),
            'phan-pure' => new PureTagDefinition(),
            PhanClosureScopeTagDefinition::NAME => new PhanClosureScopeTagDefinition(),
            PhanConstructorUsedForSideEffectsTagDefinition::NAME => new PhanConstructorUsedForSideEffectsTagDefinition(),
            PhanFileSuppressTagDefinition::NAME => new PhanFileSuppressTagDefinition(),
            PhanSuppressCurrentLineTagDefinition::NAME => new PhanSuppressCurrentLineTagDefinition(),
            PhanSuppressNextLineTagDefinition::NAME => new PhanSuppressNextLineTagDefinition(),
            PhanSuppressNextNextLineTagDefinition::NAME => new PhanSuppressNextNextLineTagDefinition(),
            PhanSuppressPreviousLineTagDefinition::NAME => new PhanSuppressPreviousLineTagDefinition(),
            PhanHardcodeReturnTypeTagDefinition::NAME => new PhanHardcodeReturnTypeTagDefinition(),
            PhanRealReturnTagDefinition::NAME => new PhanRealReturnTagDefinition(),
            PhanForbidUndeclaredMagicMethodsTagDefinition::NAME => new PhanForbidUndeclaredMagicMethodsTagDefinition(),
            PhanForbidUndeclaredMagicPropertiesTagDefinition::NAME => new PhanForbidUndeclaredMagicPropertiesTagDefinition(),
            PhanSideEffectFreeTagDefinition::NAME => new PhanSideEffectFreeTagDefinition(),
            PhanTransientTagDefinition::NAME => new PhanTransientTagDefinition(),
            PhanWriteOnlyTagDefinition::NAME => new PhanWriteOnlyTagDefinition(),
            PhanOutputReferenceTagDefinition::NAME => new PhanOutputReferenceTagDefinition(),
        ];
    }

    /**
     * @var iterable<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    public iterable $aliases {
        get => [
            'phan-abstract' => AbstractTagDefinition::NAME,
            'phanclosurescope' => PhanClosureScopeTagDefinition::NAME,
            'phan-extends' => ExtendsTagDefinition::NAME,
            'phan-immutable' => ImmutableTagDefinition::NAME,
            'phan-inherits' => ExtendsTagDefinition::NAME,
            'phan-method' => MethodTagDefinition::NAME,
            'phan-mixin' => MixinTagDefinition::NAME,
            'phan-override' => OverrideTagDefinition::NAME,
            'phan-param' => ParamTagDefinition::NAME,
            'phan-property' => PropertyTagDefinition::NAME,
            'phan-property-read' => PropertyReadTagDefinition::NAME,
            'phan-property-write' => PropertyWriteTagDefinition::NAME,
            'phan-read-only' => ReadonlyTagDefinition::NAME,
            'phan-return' => ReturnTagDefinition::NAME,
            'phan-template' => TemplateTagDefinition::NAME,
            'phan-unused-param' => UnusedParamTagDefinition::NAME,
            'phan-var' => VarTagDefinition::NAME,
        ];
    }
}
