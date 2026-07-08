<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\NoinspectionTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@noinspection`" tag suppresses the named IDE inspection for the
 * element that follows it.
 *
 * ```
 * "@noinspection" <Name> [ <Description> ]
 * ```
 */
final class NoinspectionTagDefinition extends TagDefinition
{
    public const string NAME = 'noinspection';

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

    public function create(string $name, TagPayload $result): NoinspectionTag
    {
        /** @var non-empty-string $identifier */
        $identifier = $result->get('identifier');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new NoinspectionTag($name, $identifier, $description);
    }
}
