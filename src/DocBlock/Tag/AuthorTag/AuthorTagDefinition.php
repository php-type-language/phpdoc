<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AuthorTag;

use TypeLang\PhpDoc\DocBlock\Combinator\AuthorNameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\EmailCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@author` tag documents the author of an element.
 *
 * ```
 * "@author" <AuthorName> [ "<" <Email> ">" ] [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpdocumentor.xml, edit the model instead
 */
final class AuthorTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'author';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(AuthorNameCombinator::NAME, 'author'),
                Spec::maybe(
                    Spec::sequence(
                        Spec::literal('<'),
                        Spec::rule(EmailCombinator::NAME, 'email'),
                        Spec::literal('>'),
                    ),
                ),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): AuthorTag
    {
        /** @var non-empty-string */
        $author = $result->get('author');

        /** @var non-empty-string|null */
        $email = $result->find('email');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new AuthorTag(
            name: $name,
            author: $author,
            email: $email,
            description: $description,
        );
    }
}
