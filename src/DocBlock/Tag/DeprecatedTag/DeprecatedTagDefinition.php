<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\DeprecatedTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VersionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@deprecated` tag marks an element as deprecated, optionally since
 * a given version.
 *
 * ```
 * "@deprecated" [ <Version> ] [ <Description> ]
 * ```
 */
final class DeprecatedTagDefinition extends TagDefinition
{
    public const string NAME = 'deprecated';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::maybe(Spec::rule(VersionCombinator::NAME, 'version')),
                Spec::maybe(Spec::rule(DescriptionCombinator::NAME, 'description')),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): DeprecatedTag
    {
        /** @var non-empty-string|null $version */
        $version = $result->find('version');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new DeprecatedTag($name, $version, $description);
    }
}
