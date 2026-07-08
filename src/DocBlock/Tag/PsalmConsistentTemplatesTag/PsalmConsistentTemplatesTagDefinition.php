<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmConsistentTemplatesTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-consistent-templates` tag requires that all subclasses use
 * the same template parameters as the parent.
 *
 * ```
 * "@psalm-consistent-templates" [ <Description> ]
 * ```
 */
final class PsalmConsistentTemplatesTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-consistent-templates';

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

    public function create(string $name, TagPayload $result): PsalmConsistentTemplatesTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmConsistentTemplatesTag($name, $description);
    }
}
