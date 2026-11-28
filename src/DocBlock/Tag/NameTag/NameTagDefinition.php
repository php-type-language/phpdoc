<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\NameTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@name` tag assigns an alias to a procedural page or global variable so
 * that it can be referred to by that alias in the generated documentation.
 *
 * ```
 * "@name" <Name> [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpdocumentor.xml, edit the model instead
 */
final class NameTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'name';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(NameCombinator::NAME, 'alias'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@name` tag assigns an alias to a procedural page or global variable so
                that it can be referred to by that alias in the generated documentation.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): NameTag
    {
        /** @var non-empty-string */
        $alias = $result->get('alias');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new NameTag(
            name: $name,
            alias: $alias,
            description: $description,
        );
    }
}
