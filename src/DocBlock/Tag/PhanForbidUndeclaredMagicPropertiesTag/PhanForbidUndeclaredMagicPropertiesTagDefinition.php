<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanForbidUndeclaredMagicPropertiesTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@phan-forbid-undeclared-magic-properties` tag forbids accessing
 * undeclared magic properties on the class it decorates, so only
 * documented properties may be accessed.
 *
 * ```
 * "@phan-forbid-undeclared-magic-properties" [ <Description> ]
 * ```
 */
final class PhanForbidUndeclaredMagicPropertiesTagDefinition extends TagDefinition
{
    public const string NAME = 'phan-forbid-undeclared-magic-properties';

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

    public function create(string $name, TagPayload $result): PhanForbidUndeclaredMagicPropertiesTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PhanForbidUndeclaredMagicPropertiesTag($name, $description);
    }
}
