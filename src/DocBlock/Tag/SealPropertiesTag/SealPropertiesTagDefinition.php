<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SealPropertiesTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@seal-properties`" tag forbids declaring magic properties beyond those
 * already documented.
 *
 * ```
 * "@seal-properties" [ <Description> ]
 * ```
 */
final class SealPropertiesTagDefinition extends TagDefinition
{
    public const string NAME = 'seal-properties';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): SealPropertiesTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new SealPropertiesTag($name, $description);
    }
}
