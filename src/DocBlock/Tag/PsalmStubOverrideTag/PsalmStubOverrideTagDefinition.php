<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmStubOverrideTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-stub-override` tag marks a stub declaration as
 * intentionally overriding the real signature.
 *
 * ```
 * "@psalm-stub-override" [ <Description> ]
 * ```
 */
final class PsalmStubOverrideTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-stub-override';

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

    public function create(string $name, TagPayload $result): PsalmStubOverrideTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmStubOverrideTag($name, $description);
    }
}
