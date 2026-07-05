<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\LicenseTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\UrlReference;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;

/**
 * The "@license" tag pointing to the license text by a URL.
 */
final class LicenseTag extends Tag
{
    public function __construct(
        string $name,
        public ?UrlReference $url = null,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s', $this->name);

        if ($this->url !== null) {
            $result .= ' ' . $this->url;
        }

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
