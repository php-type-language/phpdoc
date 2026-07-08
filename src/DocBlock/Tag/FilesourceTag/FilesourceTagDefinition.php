<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\FilesourceTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@filesource` tag tells documentation tooling to include the source of
 * the current file in its output.
 *
 * ```
 * "@filesource" [ <Description> ]
 * ```
 */
final class FilesourceTagDefinition extends TagDefinition
{
    public const string NAME = 'filesource';

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

    public function create(string $name, TagPayload $result): FilesourceTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new FilesourceTag($name, $description);
    }
}
