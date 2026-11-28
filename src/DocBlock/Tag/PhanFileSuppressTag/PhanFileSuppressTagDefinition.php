<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanFileSuppressTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IssueNameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@​phan-file-suppress` tag silences the listed issue types for the
 * whole file it appears in, rather than just a single element or line.
 *
 * ```
 * "@​phan-file-suppress" <IssueName> [ "," <IssueName> ... ] [ <Description> ]
 * ```
 *
 * @generated from resources/model/phan.xml, edit the model instead
 */
final class PhanFileSuppressTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'phan-file-suppress';
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
            description: <<<'TEXT'
                The `@phan-file-suppress` tag silences the listed issue types for the
                whole file it appears in, rather than just a single element or line.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PhanFileSuppressTag
    {
        /** @var non-empty-list<non-empty-string> */
        $issues = $result->getAll('issue');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PhanFileSuppressTag(
            name: $name,
            issues: $issues,
            description: $description,
        );
    }
}
