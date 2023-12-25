<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\TagFactory;

use TypeLang\PhpDocParser\DocBlock\Tag\TemplateTag;

/**
 * @template-extends TypedTagFactory<TemplateTag>
 */
final class TemplateTagFactory extends TypedTagFactory
{
    public function create(string $tag): TemplateTag
    {
        if (\trim($tag) === '') {
            throw new \InvalidArgumentException('Template parameter name required');
        }

        $suffix = \strpbrk($tag, " \t\n\r\0\x0B");

        if ($suffix === false) {
            return new TemplateTag($tag);
        }

        $typeName = \substr($tag, 0, -\strlen($suffix));

        \preg_match('/^\s+of\s+(.+?)$/isum', $suffix, $matches);

        if ($matches === []) {
            return new TemplateTag(
                alias: $typeName,
                description: $this->createDescription($suffix),
            );
        }

        [$type, $description] = $this->types->extractTypeOrNull($matches[1]);

        return new TemplateTag(
            alias: $typeName,
            type: $type,
            description: $this->createDescription($description),
        );
    }
}
