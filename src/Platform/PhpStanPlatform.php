<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\Tag\ImmutableTag\ImmutableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ExtendsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ImplementsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\UseTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\MethodTag\MethodTagDefinition;
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
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateContravariantTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateCovariantTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ThrowsTag\ThrowsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\VarTag\VarTagDefinition;

/**
 * The PHPStan platform: the "@phpstan-*" tag family understood by PHPStan.
 *
 * Only those tags that restate an existing tag are wired up, as aliases onto it;
 * PHPStan's own analysis-specific tags carry no meaning here.
 */
final class PhpStanPlatform extends Platform
{
    /**
     * @var non-empty-string
     */
    public const string NAME = 'PHPStan';

    public private(set) string $name = self::NAME;

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
            'phpstan-throws' => ThrowsTagDefinition::NAME,
            'phpstan-use' => UseTagDefinition::NAME,
            'phpstan-var' => VarTagDefinition::NAME,
        ];
    }
}
