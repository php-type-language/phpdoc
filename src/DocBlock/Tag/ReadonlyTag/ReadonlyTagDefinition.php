<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ReadonlyTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@readonly` tag declares that a property may only be written once, during
 * initialization.
 *
 * ```
 * "@readonly" [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class ReadonlyTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'readonly';
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
                The `@readonly` tag declares that a property may only be written once, during
                initialization.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): ReadonlyTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new ReadonlyTag(
            name: $name,
            description: $description,
        );
    }
}
