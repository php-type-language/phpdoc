<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\TemplateTag;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\PHPDoc\DocBlock\Tag\OptionalTypeProviderInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * ```
 * "@template" <name> ['of' <type>] [<description>]
 * ```
 */
class TemplateTag extends Tag implements OptionalTypeProviderInterface
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $template
     */
    public function __construct(
        string $name,
        public readonly string $template,
        public readonly ?TypeStatement $type = null,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
