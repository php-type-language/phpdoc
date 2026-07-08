<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintEscapeTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@psalm-taint-escape`" tag marks a value as no longer tainted after
 * passing through the described element.
 *
 * ```
 * "@psalm-taint-escape" <Name> [ <Description> ]
 * ```
 */
final class PsalmTaintEscapeTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-taint-escape';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::rule(NameCombinator::NAME, 'identifier'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): PsalmTaintEscapeTag
    {
        /** @var non-empty-string $identifier */
        $identifier = $result->get('identifier');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmTaintEscapeTag($name, $identifier, $description);
    }
}
