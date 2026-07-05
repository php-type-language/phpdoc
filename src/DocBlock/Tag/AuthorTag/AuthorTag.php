<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AuthorTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;

/**
 * The "@author" tag documents the author of an element.
 */
final class AuthorTag extends Tag
{
    public function __construct(
        string $name,
        /**
         * @var non-empty-string
         */
        public readonly string $author,
        /**
         * @var non-empty-string|null
         */
        public readonly ?string $email = null,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }

    public function __toString(): string
    {
        $result = \sprintf('@%s %s', $this->name, $this->author);

        if ($this->email !== null) {
            $result .= \sprintf(' <%s>', $this->email);
        }

        return $result;
    }
}
