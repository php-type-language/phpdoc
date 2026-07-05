<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SourceTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IntegerCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;

/**
 * The "`@source`" tag points at a range of lines of the documented element's
 * source.
 *
 * ```
 * "@source" <Start> [ <Count> ] [ <Description> ]
 * ```
 */
final class SourceTagDefinition extends TagDefinition
{
    public const string NAME = 'source';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::rule(IntegerCombinator::NAME, 'start'),
                Spec::maybe(
                    Spec::rule(IntegerCombinator::NAME, 'count'),
                ),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            isInline: false,
        );
    }

    public function create(string $name, TagPayload $result): SourceTag
    {
        /** @var int<0, max> $start */
        $start = $result->get('start');

        /** @var int<0, max>|null $count */
        $count = $result->find('count');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new SourceTag($name, $start, $count, $description);
    }
}
