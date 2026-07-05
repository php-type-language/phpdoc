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

/**
 * The "`@author`" tag documents the author of an element, together with an
 * optional email address.
 *
 * ```
 * "@author" <AuthorName> [ "<" <Email> ">" ] [ <Description> ]
 * ```
 */
final class AuthorTagDefinition extends TagDefinition
{
    public const string NAME = 'author';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
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
            isInline: false,
        );
    }

    public function create(string $name, TagPayload $result): AuthorTag
    {
        /** @var non-empty-string $author */
        $author = $result->get('author');

        /** @var non-empty-string|null $email */
        $email = $result->find('email');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new AuthorTag(
            name: self::NAME,
            author: $author,
            email: $email,
            description: $description,
        );
    }
}
