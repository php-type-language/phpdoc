<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\TagFactory;

use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Reader\OptionalTypeReader;
use TypeLang\PhpDocParser\DocBlock\Reader\Reader;
use TypeLang\PhpDocParser\DocBlock\Tag\TemplateTag;

/**
 * @template-extends TagFactory<TemplateTag>
 */
final class TemplateTagFactory extends TagFactory
{
    private readonly OptionalTypeReader $types;

    public function __construct(
        Parser $parser = new Parser(true),
        ?DescriptionFactoryInterface $descriptions = null,
    ) {
        $this->types = new OptionalTypeReader($parser);

        parent::__construct($descriptions);
    }

    public function create(string $content): TemplateTag
    {
        $content = \ltrim($content);

        if (\trim($content) === '') {
            throw new \InvalidArgumentException('Template parameter name required');
        }

        // Read template alias "@template <ALIAS> ..."
        $alias = Reader::findUpToSpace($content);

        if ($alias === '') {
            return new TemplateTag($content);
        }

        // >> Shift template alias from content.
        $content = \substr($content, \strlen($alias));

        // Read template type prefix "@template <alias> OF ..."
        \preg_match('/^(\s*of\s+)(.+?)$/isum', $content, $matches);

        // In case of "of" prefix is not defined, then return
        // other captured content as description.
        if ($matches === []) {
            $description = $this->createDescription($content);

            return new TemplateTag($alias, null, $description);
        }

        // Read template type "@template <alias> of <TYPE> ..."
        $type = $this->types->read($matches[2]);

        // In case of type is not defined, then return empty type
        // and use "of ..." as description.
        if ($type === null) {
            $description = $this->createOptionalDescription($content);

            return new TemplateTag($alias, null, $description);
        }

        // >> Shift template type from content.
        $content = \substr($content, \strlen($matches[1]) + $type->offset);

        $description = $this->createDescription(\trim($content));

        return new TemplateTag($alias, $type->data, $description);
    }
}
