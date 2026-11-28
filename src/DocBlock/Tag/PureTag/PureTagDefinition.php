<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PureTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@pure` tag declares a function or method as pure, meaning it is
 * free of side effects: calling it repeatedly with the same arguments
 * always produces the same result, without observably mutating any state.
 *
 * ```
 * "@pure" [ <Description> ]
 * ```
 *
 * @generated from resources/model/shared.xml, edit the model instead
 */
final class PureTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'pure';
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
                The `@pure` tag declares a function or method as pure, meaning it is
                free of side effects: calling it repeatedly with the same arguments
                always produces the same result, without observably mutating any state.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PureTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PureTag(
            name: $name,
            description: $description,
        );
    }
}
