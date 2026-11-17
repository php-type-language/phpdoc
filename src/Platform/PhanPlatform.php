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
use TypeLang\PhpDoc\DocBlock\Tag\TypeAliasTag\TypeAliasTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\UnusedParamTag\UnusedParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\VarTag\VarTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;

/**
 * The Phan platform: the `@phan-*` tag family understood by Phan.
 *
 * A prefixed tag that restates a common one gets the definition of that tag,
 * Phan's own marker tags are contributed as their own flag definitions.
 *
 * @generated from resources/model/phan.xml, edit the model instead
 */
final class PhanPlatform extends Platform
{
    /**
     * @return iterable<non-empty-lowercase-string, TagDefinitionInterface>
     */
    public function getTags(): iterable
    {
        return [
            'phan-abstract' => new AbstractTagDefinition(),
            'phan-assert' => new AssertTagDefinition(),
            'phan-assert-if-false' => new AssertIfFalseTagDefinition(),
            'phan-assert-if-true' => new AssertIfTrueTagDefinition(),
            PhanClosureScopeTagDefinition::getCanonicalName() => new PhanClosureScopeTagDefinition(),
            PhanConstructorUsedForSideEffectsTagDefinition::getCanonicalName() => new PhanConstructorUsedForSideEffectsTagDefinition(),
            'phan-extends' => new ExtendsTagDefinition(),
            PhanFileSuppressTagDefinition::getCanonicalName() => new PhanFileSuppressTagDefinition(),
            PhanForbidUndeclaredMagicMethodsTagDefinition::getCanonicalName() => new PhanForbidUndeclaredMagicMethodsTagDefinition(),
            PhanForbidUndeclaredMagicPropertiesTagDefinition::getCanonicalName() => new PhanForbidUndeclaredMagicPropertiesTagDefinition(),
            PhanHardcodeReturnTypeTagDefinition::getCanonicalName() => new PhanHardcodeReturnTypeTagDefinition(),
            'phan-immutable' => new ImmutableTagDefinition(),
            'phan-inherits' => new ExtendsTagDefinition(),
            'phan-method' => new MethodTagDefinition(),
            'phan-mixin' => new MixinTagDefinition(),
            PhanOutputReferenceTagDefinition::getCanonicalName() => new PhanOutputReferenceTagDefinition(),
            'phan-override' => new OverrideTagDefinition(),
            'phan-param' => new ParamTagDefinition(),
            'phan-property' => new PropertyTagDefinition(),
            'phan-property-read' => new PropertyReadTagDefinition(),
            'phan-property-write' => new PropertyWriteTagDefinition(),
            'phan-pure' => new PureTagDefinition(),
            'phan-read-only' => new ReadonlyTagDefinition(),
            PhanRealReturnTagDefinition::getCanonicalName() => new PhanRealReturnTagDefinition(),
            'phan-return' => new ReturnTagDefinition(),
            PhanSideEffectFreeTagDefinition::getCanonicalName() => new PhanSideEffectFreeTagDefinition(),
            PhanSuppressCurrentLineTagDefinition::getCanonicalName() => new PhanSuppressCurrentLineTagDefinition(),
            PhanSuppressNextLineTagDefinition::getCanonicalName() => new PhanSuppressNextLineTagDefinition(),
            PhanSuppressNextNextLineTagDefinition::getCanonicalName() => new PhanSuppressNextNextLineTagDefinition(),
            PhanSuppressPreviousLineTagDefinition::getCanonicalName() => new PhanSuppressPreviousLineTagDefinition(),
            'phan-template' => new TemplateTagDefinition(),
            PhanTransientTagDefinition::getCanonicalName() => new PhanTransientTagDefinition(),
            'phan-type' => new TypeAliasTagDefinition(),
            'phan-unused-param' => new UnusedParamTagDefinition(),
            'phan-var' => new VarTagDefinition(),
            PhanWriteOnlyTagDefinition::getCanonicalName() => new PhanWriteOnlyTagDefinition(),
        ];
    }

    /**
     * @return iterable<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    public function getAliases(): iterable
    {
        return [
            'phanclosurescope' => 'phan-closure-scope',
        ];
    }
}
