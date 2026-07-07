<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\Tag\AbstractTag\AbstractTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ImmutableTag\ImmutableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag\ExtendsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\MethodTag\MethodTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\MixinTag\MixinTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\OverrideTag\OverrideTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ParamTag\ParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyReadTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PropertyTag\PropertyWriteTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReadonlyTag\ReadonlyTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ReturnTag\ReturnTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\UnusedParamTag\UnusedParamTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\VarTag\VarTagDefinition;

/**
 * The Phan platform: the "@phan-*" tag family understood by Phan.
 *
 * Only those tags that restate an existing tag are wired up, as aliases onto it;
 * Phan's own analysis-specific tags carry no meaning here.
 */
final class PhanPlatform extends Platform
{
    /**
     * @var non-empty-string
     */
    public const string NAME = 'Phan';

    public private(set) string $name = self::NAME;

    /**
     * @var iterable<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    public iterable $aliases {
        get => [
            'phan-abstract' => AbstractTagDefinition::NAME,
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
