<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintUnescapeTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@psalm-taint-unescape`" tag marks a value as tainted again after
 * passing through the described element, reversing an earlier
 * `@psalm-taint-escape`.
 *
 * ```
 * "@psalm-taint-unescape" <Name> [ <Description> ]
 * ```
 */
final class PsalmTaintUnescapeTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-taint-unescape';

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

    public function create(string $name, TagPayload $result): PsalmTaintUnescapeTag
    {
        /** @var non-empty-string $identifier */
        $identifier = $result->get('identifier');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmTaintUnescapeTag($name, $identifier, $description);
    }
}
