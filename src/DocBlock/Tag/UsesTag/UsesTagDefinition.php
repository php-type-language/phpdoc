<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\UsesTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\ReferenceCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\CodeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@uses`" tag indicates that the described element uses the referenced
 * one.
 *
 * ```
 * "@uses" <Reference> [ <Description> ]
 * ```
 */
final class UsesTagDefinition extends TagDefinition
{
    public const string NAME = 'uses';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::rule(ReferenceCombinator::NAME, 'reference'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): UsesTag
    {
        /** @var CodeReference $reference */
        $reference = $result->get('reference');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new UsesTag($name, $reference, $description);
    }
}
