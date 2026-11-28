<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ExampleTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IntegerCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\UriReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;

/**
 * The `@example` tag points at an external source that illustrates the use of
 * the documented element, optionally narrowing it to a range of lines starting
 * at a line and spanning a number of lines.
 *
 * ```
 * "@example" ( <URL> | <URI> ) [ <StartLine> [ <LinesCount> ] ] [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpdocumentor.xml, edit the model instead
 */
final class ExampleTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'example';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::oneOf(
                    Spec::rule(UrlCombinator::NAME, 'location'),
                    Spec::rule(UriCombinator::NAME, 'location'),
                ),
                Spec::maybe(
                    Spec::sequence(
                        Spec::rule(IntegerCombinator::NAME, 'start', 'StartLine'),
                        Spec::maybe(
                            Spec::rule(IntegerCombinator::NAME, 'count', 'LinesCount'),
                        ),
                    ),
                ),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            description: <<<'TEXT'
                The `@example` tag points at an external source that illustrates the use of
                the documented element, optionally narrowing it to a range of lines starting
                at a line and spanning a number of lines.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): ExampleTag
    {
        /** @var UriReference */
        $location = $result->get('location');

        /** @var int<0, max>|null */
        $start = $result->find('start');

        /** @var int<0, max>|null */
        $count = $result->find('count');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new ExampleTag(
            name: $name,
            location: $location,
            start: $start,
            count: $count,
            description: $description,
        );
    }
}
