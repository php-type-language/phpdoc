<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\Tag\AbstractTag\AbstractTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ApiTag\ApiTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\DeprecatedTag\DeprecatedTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\FinalTag\FinalTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ImmutableTag\ImmutableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ExtendsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ImplementsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\UseTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InternalTag\InternalTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\LinkTag\LinkTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\MethodTag\MethodTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\MixinTag\MixinTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\NoNamedArgumentsTag\NoNamedArgumentsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\NotDeprecatedTag\NotDeprecatedTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\OverrideTag\OverrideTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamClosureThisTag\ParamClosureThisTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamImmediatelyInvokedCallableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamLaterInvokedCallableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamOutTag\ParamOutTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyReadTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyWriteTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PureUnlessCallableIsImpureTag\PureUnlessCallableIsImpureTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReadonlyTag\ReadonlyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\RequireInheritanceTag\RequireExtendsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\RequireInheritanceTag\RequireImplementsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReturnTag\ReturnTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SealMethodsTag\SealMethodsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SealPropertiesTag\SealPropertiesTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SeeTag\SeeTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SuppressTag\SuppressTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateContravariantTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateCovariantTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ThrowsTag\ThrowsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\UnusedParamTag\UnusedParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\VarTag\VarTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;

/**
 * The standard platform: the common tags, without a tool prefix, understood by
 * most of the documentation and static analysis tools.
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class StandardPlatform extends Platform
{
    /**
     * @return iterable<non-empty-lowercase-string, TagDefinitionInterface>
     */
    public function getTags(): iterable
    {
        return [
            AbstractTagDefinition::getCanonicalName() => new AbstractTagDefinition(),
            ApiTagDefinition::getCanonicalName() => new ApiTagDefinition(),
            DeprecatedTagDefinition::getCanonicalName() => new DeprecatedTagDefinition(),
            ExtendsTagDefinition::getCanonicalName() => new ExtendsTagDefinition(),
            FinalTagDefinition::getCanonicalName() => new FinalTagDefinition(),
            ImmutableTagDefinition::getCanonicalName() => new ImmutableTagDefinition(),
            ImplementsTagDefinition::getCanonicalName() => new ImplementsTagDefinition(),
            InternalTagDefinition::getCanonicalName() => new InternalTagDefinition(),
            LinkTagDefinition::getCanonicalName() => new LinkTagDefinition(),
            MethodTagDefinition::getCanonicalName() => new MethodTagDefinition(),
            MixinTagDefinition::getCanonicalName() => new MixinTagDefinition(),
            NoNamedArgumentsTagDefinition::getCanonicalName() => new NoNamedArgumentsTagDefinition(),
            NotDeprecatedTagDefinition::getCanonicalName() => new NotDeprecatedTagDefinition(),
            OverrideTagDefinition::getCanonicalName() => new OverrideTagDefinition(),
            ParamTagDefinition::getCanonicalName() => new ParamTagDefinition(),
            ParamClosureThisTagDefinition::getCanonicalName() => new ParamClosureThisTagDefinition(),
            ParamImmediatelyInvokedCallableTagDefinition::getCanonicalName() => new ParamImmediatelyInvokedCallableTagDefinition(),
            ParamLaterInvokedCallableTagDefinition::getCanonicalName() => new ParamLaterInvokedCallableTagDefinition(),
            ParamOutTagDefinition::getCanonicalName() => new ParamOutTagDefinition(),
            PropertyTagDefinition::getCanonicalName() => new PropertyTagDefinition(),
            PropertyReadTagDefinition::getCanonicalName() => new PropertyReadTagDefinition(),
            PropertyWriteTagDefinition::getCanonicalName() => new PropertyWriteTagDefinition(),
            PureUnlessCallableIsImpureTagDefinition::getCanonicalName() => new PureUnlessCallableIsImpureTagDefinition(),
            ReadonlyTagDefinition::getCanonicalName() => new ReadonlyTagDefinition(),
            RequireExtendsTagDefinition::getCanonicalName() => new RequireExtendsTagDefinition(),
            RequireImplementsTagDefinition::getCanonicalName() => new RequireImplementsTagDefinition(),
            ReturnTagDefinition::getCanonicalName() => new ReturnTagDefinition(),
            SealMethodsTagDefinition::getCanonicalName() => new SealMethodsTagDefinition(),
            SealPropertiesTagDefinition::getCanonicalName() => new SealPropertiesTagDefinition(),
            SeeTagDefinition::getCanonicalName() => new SeeTagDefinition(),
            SuppressTagDefinition::getCanonicalName() => new SuppressTagDefinition(),
            TemplateTagDefinition::getCanonicalName() => new TemplateTagDefinition(),
            TemplateContravariantTagDefinition::getCanonicalName() => new TemplateContravariantTagDefinition(),
            TemplateCovariantTagDefinition::getCanonicalName() => new TemplateCovariantTagDefinition(),
            ThrowsTagDefinition::getCanonicalName() => new ThrowsTagDefinition(),
            UnusedParamTagDefinition::getCanonicalName() => new UnusedParamTagDefinition(),
            UseTagDefinition::getCanonicalName() => new UseTagDefinition(),
            VarTagDefinition::getCanonicalName() => new VarTagDefinition(),
        ];
    }

    /**
     * @return iterable<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    public function getAliases(): iterable
    {
        return [
            'inherits' => 'extends',
            'returns' => 'return',
            'template-extends' => 'extends',
            'template-implements' => 'implements',
            'template-invariant' => 'template',
            'template-use' => 'use',
            'throw' => 'throws',
        ];
    }
}
