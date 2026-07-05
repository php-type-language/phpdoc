<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\TodoTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;

/**
 * The "`@todo`" tag records a task that still needs to be done for an element.
 *
 * ```
 * "@todo" [ <Description> ]
 * ```
 */
final class TodoTagDefinition extends TagDefinition
{
    public const string NAME = 'todo';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            isInline: false,
        );
    }

    public function create(string $name, TagPayload $result): TodoTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new TodoTag($name, $description);
    }
}
