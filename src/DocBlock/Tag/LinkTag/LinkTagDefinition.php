<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\LinkTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\UriReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;

/**
 * The `@link` tag can be used to define a relation, or link, between
 * the element, or part of the long description when used inline, to a URI.
 *
 * The URI MUST be complete and well-formed as specified in RFC2396.
 *
 * The `@link` tag MAY have a description appended to indicate the type of
 * relation defined by this occurrence.
 *
 * ```
 * "@link" <URI> [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class LinkTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'link';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(UriCombinator::NAME, 'uri'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
        );
    }

    public function create(string $name, TagPayload $result): LinkTag
    {
        /** @var UriReference */
        $uri = $result->get('uri');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new LinkTag(
            name: $name,
            uri: $uri,
            description: $description,
        );
    }
}
