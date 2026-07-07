<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\Tag\ApiTag\ApiTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ImmutableTag\ImmutableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ExtendsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ImplementsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\UseTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InternalTag\InternalTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\MethodTag\MethodTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamOutTag\ParamOutTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyReadTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyWriteTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReadonlyTag\ReadonlyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\RequireInheritanceTag\RequireExtendsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\RequireInheritanceTag\RequireImplementsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReturnTag\ReturnTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SealMethodsTag\SealMethodsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SealPropertiesTag\SealPropertiesTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SuppressTag\SuppressTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateContravariantTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateCovariantTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\VarTag\VarTagDefinition;

/**
 * The Psalm platform: the "@psalm-*" tag family understood by Psalm.
 *
 * Only those tags that restate an existing tag are wired up, as aliases onto it;
 * Psalm's own analysis-specific tags carry no meaning here.
 */
final class PsalmPlatform extends Platform
{
    /**
     * @var non-empty-string
     */
    public const string NAME = 'Psalm';

    public private(set) string $name = self::NAME;

    /**
     * @var iterable<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    public iterable $aliases {
        get => [
            'psalm-api' => ApiTagDefinition::NAME,
            'psalm-extends' => ExtendsTagDefinition::NAME,
            'psalm-immutable' => ImmutableTagDefinition::NAME,
            'psalm-implements' => ImplementsTagDefinition::NAME,
            'psalm-internal' => InternalTagDefinition::NAME,
            'psalm-method' => MethodTagDefinition::NAME,
            'psalm-param' => ParamTagDefinition::NAME,
            'psalm-param-out' => ParamOutTagDefinition::NAME,
            'psalm-property' => PropertyTagDefinition::NAME,
            'psalm-property-read' => PropertyReadTagDefinition::NAME,
            'psalm-property-write' => PropertyWriteTagDefinition::NAME,
            'psalm-readonly' => ReadonlyTagDefinition::NAME,
            'psalm-require-extends' => RequireExtendsTagDefinition::NAME,
            'psalm-require-implements' => RequireImplementsTagDefinition::NAME,
            'psalm-return' => ReturnTagDefinition::NAME,
            'psalm-seal-methods' => SealMethodsTagDefinition::NAME,
            'psalm-seal-properties' => SealPropertiesTagDefinition::NAME,
            'psalm-suppress' => SuppressTagDefinition::NAME,
            'psalm-template' => TemplateTagDefinition::NAME,
            'psalm-template-contravariant' => TemplateContravariantTagDefinition::NAME,
            'psalm-template-covariant' => TemplateCovariantTagDefinition::NAME,
            'psalm-use' => UseTagDefinition::NAME,
            'psalm-var' => VarTagDefinition::NAME,
        ];
    }
}
