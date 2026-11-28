<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\InternalTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;

/**
 * The `@internal` tag marks an element as internal to its package, or documents
 * information meant only for the maintainers of that package.
 *
 * ```
 * "@internal" [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class InternalTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'internal';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            description: <<<'TEXT'
                The `@internal` tag marks an element as internal to its package, or documents
                information meant only for the maintainers of that package.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): InternalTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new InternalTag(
            name: $name,
            description: $description,
        );
    }
}
