<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\ReferenceInterface;

/**
 * A tag whose body is a reference to another element or an external resource,
 * followed by an optional description.
 *
 * @template-covariant TReference of ReferenceInterface = ReferenceInterface
 *
 * @template-implements ReferencedTagInterface<TReference>
 */
abstract class ReferenceTag extends Tag implements ReferencedTagInterface
{
    public function __construct(
        string $name,
        /**
         * @var TReference
         */
        public readonly ReferenceInterface $reference,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s %s', $this->name, $this->reference);

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
