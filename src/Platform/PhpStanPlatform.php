<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\Tag\AllowPrivateMutationTag\AllowPrivateMutationTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AssertIfFalseTag\AssertIfFalseTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AssertIfTrueTag\AssertIfTrueTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AssertTag\AssertTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ConsistentConstructorTag\ConsistentConstructorTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ImmutableTag\ImmutableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ImportTypeAliasTag\ImportTypeAliasTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ExtendsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ImplementsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\UseTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\MethodTag\MethodTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamClosureThisTag\ParamClosureThisTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamImmediatelyInvokedCallableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag\ParamLaterInvokedCallableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamOutTag\ParamOutTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhpStanIgnoreLineTag\PhpStanIgnoreLineTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhpStanIgnoreNextLineTag\PhpStanIgnoreNextLineTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhpStanIgnoreTag\PhpStanIgnoreTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhpStanImpureTag\PhpStanImpureTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyReadTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyWriteTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PureTag\PureTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PureUnlessCallableIsImpureTag\PureUnlessCallableIsImpureTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReadonlyAllowPrivateMutationTag\ReadonlyAllowPrivateMutationTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReadonlyTag\ReadonlyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\RequireInheritanceTag\RequireExtendsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\RequireInheritanceTag\RequireImplementsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReturnTag\ReturnTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SelfOutTag\SelfOutTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateContravariantTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateCovariantTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ThrowsTag\ThrowsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TypeAliasTag\TypeAliasTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\VarTag\VarTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\YieldTag\YieldTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;

/**
 * The PHPStan platform: the `@phpstan-*` tag family understood by PHPStan.
 *
 * Tags that restate an existing one are wired as aliases onto it; PHPStan's own
 * marker tags are contributed as their own flag definitions.
 */
final class PhpStanPlatform extends Platform
{
    /**
     * @var iterable<non-empty-lowercase-string, TagDefinitionInterface>
     */
    public iterable $tags {
        get => [
            'phpstan-allow-private-mutation' => new AllowPrivateMutationTagDefinition(),
            'phpstan-assert' => new AssertTagDefinition(),
            'phpstan-assert-if-true' => new AssertIfTrueTagDefinition(),
            'phpstan-assert-if-false' => new AssertIfFalseTagDefinition(),
            'phpstan-consistent-constructor' => new ConsistentConstructorTagDefinition(),
            'phpstan-import-type' => new ImportTypeAliasTagDefinition(),
            'phpstan-pure' => new PureTagDefinition(),
            'phpstan-readonly-allow-private-mutation' => new ReadonlyAllowPrivateMutationTagDefinition(),
            'phpstan-self-out' => new SelfOutTagDefinition(),
            'phpstan-type' => new TypeAliasTagDefinition(),
            'phpstan-yield' => new YieldTagDefinition(),
            PhpStanImpureTagDefinition::NAME => new PhpStanImpureTagDefinition(),
            PhpStanIgnoreTagDefinition::NAME => new PhpStanIgnoreTagDefinition(),
            PhpStanIgnoreLineTagDefinition::NAME => new PhpStanIgnoreLineTagDefinition(),
            PhpStanIgnoreNextLineTagDefinition::NAME => new PhpStanIgnoreNextLineTagDefinition(),
        ];
    }

    /**
     * @var iterable<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    public iterable $aliases {
        get => [
            'phpstan-extends' => ExtendsTagDefinition::NAME,
            'phpstan-immutable' => ImmutableTagDefinition::NAME,
            'phpstan-implements' => ImplementsTagDefinition::NAME,
            'phpstan-method' => MethodTagDefinition::NAME,
            'phpstan-param' => ParamTagDefinition::NAME,
            'phpstan-param-closure-this' => ParamClosureThisTagDefinition::NAME,
            'phpstan-param-immediately-invoked-callable' => ParamImmediatelyInvokedCallableTagDefinition::NAME,
            'phpstan-param-later-invoked-callable' => ParamLaterInvokedCallableTagDefinition::NAME,
            'phpstan-param-out' => ParamOutTagDefinition::NAME,
            'phpstan-property' => PropertyTagDefinition::NAME,
            'phpstan-property-read' => PropertyReadTagDefinition::NAME,
            'phpstan-property-write' => PropertyWriteTagDefinition::NAME,
            'phpstan-pure-unless-callable-is-impure' => PureUnlessCallableIsImpureTagDefinition::NAME,
            'phpstan-readonly' => ReadonlyTagDefinition::NAME,
            'phpstan-require-extends' => RequireExtendsTagDefinition::NAME,
            'phpstan-require-implements' => RequireImplementsTagDefinition::NAME,
            'phpstan-return' => ReturnTagDefinition::NAME,
            'phpstan-template' => TemplateTagDefinition::NAME,
            'phpstan-template-contravariant' => TemplateContravariantTagDefinition::NAME,
            'phpstan-template-covariant' => TemplateCovariantTagDefinition::NAME,
            'phpstan-this-out' => 'phpstan-self-out',
            'phpstan-throws' => ThrowsTagDefinition::NAME,
            'phpstan-use' => UseTagDefinition::NAME,
            'phpstan-var' => VarTagDefinition::NAME,
        ];
    }
}
