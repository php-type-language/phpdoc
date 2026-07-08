<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanWriteOnlyTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@phan-write-only` tag declares that a property may only ever be
 * written, never read.
 *
 * ```
 * "@phan-write-only" [ <Description> ]
 * ```
 */
final class PhanWriteOnlyTagDefinition extends TagDefinition
{
    public const string NAME = 'phan-write-only';

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

    public function create(string $name, TagPayload $result): PhanWriteOnlyTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PhanWriteOnlyTag($name, $description);
    }
}
