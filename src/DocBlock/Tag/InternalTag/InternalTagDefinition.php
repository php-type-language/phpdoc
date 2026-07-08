<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\InternalTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@internal` tag marks an element as internal to its package, or, when
 * used inline, documents information meant only for that package's maintainers.
 *
 * ```
 * "@internal" [ <Description> ]
 * ```
 */
final class InternalTagDefinition extends TagDefinition
{
    public const string NAME = 'internal';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: TagPlacement::Any,
        );
    }

    public function create(string $name, TagPayload $result): InternalTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new InternalTag($name, $description);
    }
}
