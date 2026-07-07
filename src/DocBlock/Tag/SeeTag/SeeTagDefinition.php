<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SeeTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\ReferenceCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\CodeReference;
use TypeLang\PhpDoc\DocBlock\Reference\UriReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@see`" tag can be used to define a {@see CodeReference element}.
 *
 * When defining a reference to other elements, you can refer to a specific
 * element by appending a double colon and providing the name of that element
 * (also called the 'Fully Qualified Name' or _FQN_).
 *
 * A URI MUST be complete and well-formed as specified in RFC 2396.
 *
 * The "`@see"` tag SHOULD have a description to provide additional information
 * regarding the relationship between the element and its target.
 *
 * The "`@see`" tag cannot refer to a namespace element.
 *
 * ```
 * "@see" ( <Reference> | <URI> ) [ <Description> ]
 * ```
 *
 * @link https://www.ietf.org/rfc/rfc2396.txt RFC2396
 */
final class SeeTagDefinition extends TagDefinition
{
    public const string NAME = 'see';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::oneOf(
                    Spec::rule(ReferenceCombinator::NAME, 'ref'),
                    Spec::rule(UriCombinator::NAME, 'ref'),
                ),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Any,
        );
    }

    public function create(string $name, TagPayload $result): SeeTag
    {
        /** @var CodeReference|UriReference $reference */
        $reference = $result->get('ref');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new SeeTag(
            name: $name,
            reference: $reference,
            description: $description,
        );
    }
}
