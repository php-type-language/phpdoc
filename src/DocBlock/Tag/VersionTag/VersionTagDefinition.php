<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\VersionTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VersionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@version`" tag documents the current version of an element.
 *
 * ```
 * "@version" [ <Version> ] [ <Description> ]
 * ```
 */
final class VersionTagDefinition extends TagDefinition
{
    public const string NAME = 'version';

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

    public function create(string $name, TagPayload $result): VersionTag
    {
        /** @var non-empty-string|null $version */
        $version = $result->find('version');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new VersionTag($name, $version, $description);
    }
}
