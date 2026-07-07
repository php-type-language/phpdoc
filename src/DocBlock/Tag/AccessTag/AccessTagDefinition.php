<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AccessTag;

use TypeLang\PhpDoc\DocBlock\Combinator\AccessCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@access`" tag documents the visibility (access level) of an element.
 *
 * ```
 * "@access" ( <AccessVisibility> ) [ <Description> ]
 * ```
 */
final class AccessTagDefinition extends TagDefinition
{
    public const string NAME = 'access';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::rule(AccessCombinator::NAME, 'access', 'AccessVisibility'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): AccessTag
    {
        /** @var Visibility $access */
        $access = $result->get('access');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new AccessTag($name, $access, $description);
    }
}
