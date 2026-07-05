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
 * The "`@example`" tag points at an external source illustrating the use of the
 * documented element, or describes such an example inline.
 *
 * ```
 * "@example" (<URL> | <URI>) [ <Start> [ <Count> ] ] [ <Description> ]
 * ```
 */
final class ExampleTagDefinition extends TagDefinition
{
    public const string NAME = 'example';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::oneOf(
                    Spec::rule(UrlCombinator::NAME, 'location'),
                    Spec::rule(UriCombinator::NAME, 'location'),
                ),
                Spec::maybe(
                    Spec::sequence(
                        Spec::rule(IntegerCombinator::NAME, 'start'),
                        Spec::maybe(
                            Spec::rule(IntegerCombinator::NAME, 'count'),
                        ),
                    ),
                ),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            isInline: false,
        );
    }

    public function create(string $name, TagPayload $result): ExampleTag
    {
        /** @var UriReference $location */
        $location = $result->get('location');

        /** @var int<0, max>|null $start */
        $start = $result->find('start');

        /** @var int<0, max>|null $count */
        $count = $result->find('count');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new ExampleTag($name, $location, $start, $count, $description);
    }
}
