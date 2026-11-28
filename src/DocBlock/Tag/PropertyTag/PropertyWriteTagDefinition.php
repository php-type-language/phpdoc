<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PropertyTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@property-write` tag declares a magic property that can only be written.
 *
 * ```
 * "@property-write" <Type> <Variable> [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class PropertyWriteTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'property-write';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(TypeCombinator::NAME, 'type'),
                Spec::rule(VariableCombinator::NAME, 'variable'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@property-write` tag declares a magic property that can only be written.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PropertyWriteTag
    {
        /** @var TypeReference */
        $statement = $result->get('type');

        /** @var non-empty-string */
        $variable = $result->get('variable');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PropertyWriteTag(
            name: $name,
            statement: $statement,
            variable: $variable,
            description: $description,
        );
    }
}
