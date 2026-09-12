<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\ReferenceInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * A tag narrowing the type of something once the call it is written on
 * returns.
 *
 * What is narrowed is not a variable alone: an assertion is written of a
 * property or of a method just as well, which is why the subject is a
 * reference rather than a name.
 *
 * ```
 *  "@assert" [ "!" | "=" | "!=" ] <Type> <Subject> [ <Description> ]
 * ```
 */
abstract class AssertionTag extends TypedTag
{
    public function __construct(
        string $name,
        TypeReference $statement,
        /**
         * What the assertion is written of.
         */
        public readonly ReferenceInterface $subject,
        /**
         * The way the subject relates to the type.
         */
        public readonly AssertOperator $operator = AssertOperator::Is,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $statement, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf(
            '@%s %s%s %s',
            $this->name,
            $this->operator->value,
            $this->statement,
            $this->subject,
        );

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
