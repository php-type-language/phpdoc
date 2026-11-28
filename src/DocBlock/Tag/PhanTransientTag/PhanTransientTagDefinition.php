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
 *
 * @generated from resources/model/phan.xml, edit the model instead
 */
final class PhanTransientTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'phan-transient';
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
                The `@phan-transient` tag marks a property as excluded from
                serialization.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PhanTransientTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PhanTransientTag(
            name: $name,
            description: $description,
        );
    }
}
