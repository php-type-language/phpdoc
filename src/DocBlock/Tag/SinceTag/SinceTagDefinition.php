<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SinceTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VersionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;

/**
 * The "`@since`" tag documents the version at which an element became
 * available.
 *
 * ```
 * "@since" [ <Version> ] [ <Description> ]
 * ```
 */
final class SinceTagDefinition extends TagDefinition
{
    public const string NAME = 'since';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::maybe(Spec::rule(VersionCombinator::NAME, 'version')),
                Spec::maybe(Spec::rule(DescriptionCombinator::NAME, 'description')),
            ),
            isInline: false,
        );
    }

    public function create(string $name, TagPayload $result): SinceTag
    {
        /** @var non-empty-string|null $version */
        $version = $result->find('version');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new SinceTag($name, $version, $description);
    }
}
