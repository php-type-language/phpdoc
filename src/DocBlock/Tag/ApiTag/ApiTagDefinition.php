<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ApiTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@api` tag marks an element as part of the public, supported API of its
 * package.
 *
 * ```
 * "@api" [ <Description> ]
 * ```
 */
final class ApiTagDefinition extends TagDefinition
{
    public const string NAME = 'api';

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

    public function create(string $name, TagPayload $result): ApiTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new ApiTag($name, $description);
    }
}
