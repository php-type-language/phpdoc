<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SealMethodsTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@seal-methods` tag forbids declaring magic methods beyond those
 * already documented.
 *
 * ```
 * "@seal-methods" [ <Description> ]
 * ```
 */
final class SealMethodsTagDefinition extends TagDefinition
{
    public const string NAME = 'seal-methods';

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

    public function create(string $name, TagPayload $result): SealMethodsTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new SealMethodsTag($name, $description);
    }
}
