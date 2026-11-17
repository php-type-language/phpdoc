<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhpStanIgnoreTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IssueNameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@​phpstan-ignore` tag silences the listed error identifiers reported
 * on the current line.
 *
 * ```
 * "@​phpstan-ignore" <IssueName> [ "," <IssueName> ... ] [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpstan.xml, edit the model instead
 */
final class PhpStanIgnoreTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'phpstan-ignore';
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

    public function create(string $name, TagPayload $result): PhpStanIgnoreTag
    {
        /** @var non-empty-list<non-empty-string> */
        $issues = $result->getAll('issue');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PhpStanIgnoreTag(
            name: $name,
            issues: $issues,
            description: $description,
        );
    }
}
