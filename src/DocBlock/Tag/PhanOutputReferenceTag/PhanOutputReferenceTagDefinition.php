<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanOutputReferenceTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@phan-output-reference` tag marks a by-reference argument as
 * output-only.
 *
 * ```
 * "@phan-output-reference" [ <Description> ]
 * ```
 */
final class PhanOutputReferenceTagDefinition extends TagDefinition
{
    public const string NAME = 'phan-output-reference';

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

    public function create(string $name, TagPayload $result): PhanOutputReferenceTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PhanOutputReferenceTag($name, $description);
    }
}
