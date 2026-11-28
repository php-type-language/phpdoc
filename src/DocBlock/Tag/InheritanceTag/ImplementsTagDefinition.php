<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@implements` tag allows to extend templated interfaces, providing the
 * concrete type arguments of the implemented interface.
 *
 * The `@implements` tag makes a generic interface concrete by providing the
 * type arguments for its template parameters.
 *
 * ```
 * "@implements" <Type> [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class ImplementsTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'implements';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(TypeCombinator::NAME, 'type'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@implements` tag allows to extend templated interfaces, providing the
                concrete type arguments of the implemented interface.

                The `@implements` tag makes a generic interface concrete by providing the
                type arguments for its template parameters.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): ImplementsTag
    {
        /** @var TypeReference */
        $statement = $result->get('type');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new ImplementsTag(
            name: $name,
            statement: $statement,
            description: $description,
        );
    }
}
