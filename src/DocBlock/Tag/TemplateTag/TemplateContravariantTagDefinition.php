<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\TemplateTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@template-contravariant` tag declares a contravariant generic type
 * parameter.
 *
 * ```
 * "@template-contravariant" <Name> [ "of" <Type> ] [ "=" <Type> ] [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class TemplateContravariantTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'template-contravariant';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(NameCombinator::NAME, 'parameter'),
                Spec::maybe(
                    Spec::sequence(
                        Spec::literal('of'),
                        Spec::rule(TypeCombinator::NAME, 'bound'),
                    ),
                ),
                Spec::maybe(
                    Spec::sequence(
                        Spec::literal('='),
                        Spec::rule(TypeCombinator::NAME, 'default'),
                    ),
                ),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@template-contravariant` tag declares a contravariant generic type
                parameter.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): TemplateContravariantTag
    {
        /** @var non-empty-string */
        $parameter = $result->get('parameter');

        /** @var TypeReference|null */
        $bound = $result->find('bound');

        /** @var TypeReference|null */
        $default = $result->find('default');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new TemplateContravariantTag(
            name: $name,
            parameter: $parameter,
            bound: $bound,
            default: $default,
            description: $description,
        );
    }
}
