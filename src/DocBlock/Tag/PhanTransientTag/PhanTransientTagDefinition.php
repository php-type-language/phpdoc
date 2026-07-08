<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanTransientTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@phan-transient` tag marks a property as excluded from
 * serialization.
 *
 * ```
 * "@phan-transient" [ <Description> ]
 * ```
 */
final class PhanTransientTagDefinition extends TagDefinition
{
    public const string NAME = 'phan-transient';

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

    public function create(string $name, TagPayload $result): PhanTransientTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PhanTransientTag($name, $description);
    }
}
