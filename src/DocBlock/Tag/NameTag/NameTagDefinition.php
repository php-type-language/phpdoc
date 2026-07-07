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
 * The "`@name`" tag assigns an alias to a procedural page or global variable.
 *
 * ```
 * "@name" <Name> [ <Description> ]
 * ```
 */
final class NameTagDefinition extends TagDefinition
{
    public const string NAME = 'name';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::rule(NameCombinator::NAME, 'alias'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): NameTag
    {
        /** @var non-empty-string $alias */
        $alias = $result->get('alias');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new NameTag($name, $alias, $description);
    }
}
