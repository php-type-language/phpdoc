<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;

/**
 * A tag whose body is an optional version followed by an optional description.
 */
abstract class VersionedTag extends Tag implements VersionedTagInterface
{
    public function __construct(
        string $name,
        /**
         * @var non-empty-string|null
         */
        public readonly ?string $version = null,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s', $this->name);

        if ($this->version !== null) {
            $result .= ' ' . $this->version;
        }

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
