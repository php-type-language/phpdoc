<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/global.html#global
 */
final class GlobalTag extends TypedTag implements VariableNameProviderInterface
{
    /**
     * @param non-empty-string $variable
     */
    public function __construct(
        private readonly string $variable,
        TypeStatement $type,
        \Stringable|string|null $description = null
    ) {
        parent::__construct('global', $type, $description);
    }

    /**
     * @return non-empty-string
     */
    public function getVarName(): string
    {
        return $this->variable;
    }
}
