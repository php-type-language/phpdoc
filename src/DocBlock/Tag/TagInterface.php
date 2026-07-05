<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\ComponentInterface;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;

/**
 * Representation of the phpdoc tag.
 */
interface TagInterface extends ComponentInterface
{
    /**
     * Gets tag name string without the '@' prefix.
     *
     * That is, can contain all the characters that can match the PHP FQN
     * (Fully Qualified Name), as well as the '-' character.
     */
    public string $name {
        get;
    }

    /**
     * Gets an optional description object which can be represented as
     * a {@see string} and contains additional information or {@see null}
     * in case of description is not defined in the entry.
     */
    public ?DescriptionInterface $description {
        get;
    }
}
