<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmNoSealPropertiesTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-no-seal-properties` tag allows a class to declare magic
 * properties beyond those already documented.
 *
 * ```
 * "@psalm-no-seal-properties" [ <Description> ]
 * ```
 */
final class PsalmNoSealPropertiesTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-no-seal-properties';

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

    public function create(string $name, TagPayload $result): PsalmNoSealPropertiesTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmNoSealPropertiesTag($name, $description);
    }
}
