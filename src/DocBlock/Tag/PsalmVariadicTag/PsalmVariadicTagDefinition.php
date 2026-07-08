<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmVariadicTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-variadic` tag declares that a class's magic
 * `__call`/`__callStatic` methods accept a variadic list of arguments.
 *
 * ```
 * "@psalm-variadic" [ <Description> ]
 * ```
 */
final class PsalmVariadicTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-variadic';

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

    public function create(string $name, TagPayload $result): PsalmVariadicTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmVariadicTag($name, $description);
    }
}
