<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SuppressTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IssueNameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@suppress` tag silences the listed diagnostics that would otherwise be
 * reported for an element.
 *
 * ```
 * "@suppress" <IssueName> [ "," <IssueName> ... ] [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class SuppressTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'suppress';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(IssueNameCombinator::NAME, 'issue'),
                Spec::repeat(
                    Spec::sequence(
                        Spec::literal(','),
                        Spec::rule(IssueNameCombinator::NAME, 'issue'),
                    ),
                ),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): SuppressTag
    {
        /** @var non-empty-list<non-empty-string> */
        $issues = $result->getAll('issue');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new SuppressTag(
            name: $name,
            issues: $issues,
            description: $description,
        );
    }
}
