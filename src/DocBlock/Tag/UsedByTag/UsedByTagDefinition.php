<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\UsedByTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\ReferenceCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\CodeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@used-by` tag indicates that the described element is used by the
 * referenced one.
 *
 * ```
 * "@used-by" <Reference> [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpdocumentor.xml, edit the model instead
 */
final class UsedByTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'used-by';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(ReferenceCombinator::NAME, 'reference'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@used-by` tag indicates that the described element is used by the
                referenced one.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): UsedByTag
    {
        /** @var CodeReference */
        $reference = $result->get('reference');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new UsedByTag(
            name: $name,
            reference: $reference,
            description: $description,
        );
    }
}
