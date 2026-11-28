<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\GlobalTag;

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
 * The `@global` tag documents a global variable that a function relies on.
 *
 * ```
 * "@global" <Type> <Variable> [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpdocumentor.xml, edit the model instead
 */
final class GlobalTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'global';
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
                The `@global` tag documents a global variable that a function relies on.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): GlobalTag
    {
        /** @var TypeReference */
        $statement = $result->get('type');

        /** @var non-empty-string */
        $variable = $result->get('variable');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new GlobalTag(
            name: $name,
            statement: $statement,
            variable: $variable,
            description: $description,
        );
    }
}
