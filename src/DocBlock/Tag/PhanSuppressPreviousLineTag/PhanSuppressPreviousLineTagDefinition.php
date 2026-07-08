<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressPreviousLineTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IssueNameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@phan-suppress-previous-line` tag silences the listed issue types
 * reported on the previous line.
 *
 * ```
 * "@phan-suppress-previous-line" <IssueName> { "," <IssueName> } [ <Description> ]
 * ```
 */
final class PhanSuppressPreviousLineTagDefinition extends TagDefinition
{
    public const string NAME = 'phan-suppress-previous-line';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
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

    public function create(string $name, TagPayload $result): PhanSuppressPreviousLineTag
    {
        /** @var non-empty-list<non-empty-string> $issues */
        $issues = $result->getAll('issue');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PhanSuppressPreviousLineTag($name, $issues, $description);
    }
}
