<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreVarTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-ignore-var` tag excludes the immediately following `@var`
 * annotation from type inference, falling back to the inferred type
 * instead of the declared one.
 *
 * ```
 * "@psalm-ignore-var" [ <Description> ]
 * ```
 *
 * @generated from resources/model/psalm.xml, edit the model instead
 */
final class PsalmIgnoreVarTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'psalm-ignore-var';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@psalm-ignore-var` tag excludes the immediately following `@var`
                annotation from type inference, falling back to the inferred type
                instead of the declared one.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PsalmIgnoreVarTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PsalmIgnoreVarTag(
            name: $name,
            description: $description,
        );
    }
}
