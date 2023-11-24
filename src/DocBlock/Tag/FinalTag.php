<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO This tag doesnt support description: Should support for this
 *      functionality be removed?
 *
 * @link https://manual.phpdoc.org/HTMLSmartyConverter/HandS/phpDocumentor/tutorial_tags.final.pkg.html
 * @link https://pear.php.net/package/PhpDocumentor/docs/latest/phpDocumentor/tutorial_tags.final.pkg.html
 * @link https://github.com/phpstan/phpstan/discussions/5343
 * @link https://youtrack.jetbrains.com/issue/WI-31225
 */
final class FinalTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('final', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
