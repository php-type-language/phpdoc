<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmNoSealMethodsTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-no-seal-methods` tag allows a class to declare magic
 * methods beyond those already documented.
 *
 * ```
 * "@psalm-no-seal-methods" [ <Description> ]
 * ```
 */
final class PsalmNoSealMethodsTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-no-seal-methods';

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

    public function create(string $name, TagPayload $result): PsalmNoSealMethodsTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmNoSealMethodsTag($name, $description);
    }
}
