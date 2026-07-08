<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhpStanImpureTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@phpstan-impure` tag declares a function or method as impure,
 * that is, having side effects.
 *
 * ```
 * "@phpstan-impure" [ <Description> ]
 * ```
 */
final class PhpStanImpureTagDefinition extends TagDefinition
{
    public const string NAME = 'phpstan-impure';

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

    public function create(string $name, TagPayload $result): PhpStanImpureTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PhpStanImpureTag($name, $description);
    }
}
