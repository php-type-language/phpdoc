<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SourceTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IntegerCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@source` tag points at a range of lines of the documented element's
 * source, starting at a line and optionally spanning a number of lines.
 *
 * ```
 * "@source" <StartLine> [ <LinesCount> ] [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpdocumentor.xml, edit the model instead
 */
final class SourceTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'source';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(IntegerCombinator::NAME, 'start', 'StartLine'),
                Spec::maybe(
                    Spec::rule(IntegerCombinator::NAME, 'count', 'LinesCount'),
                ),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Inline,
        );
    }

    public function create(string $name, TagPayload $result): SourceTag
    {
        /** @var int<0, max> */
        $start = $result->get('start');

        /** @var int<0, max>|null */
        $count = $result->find('count');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new SourceTag(
            name: $name,
            start: $start,
            count: $count,
            description: $description,
        );
    }
}
