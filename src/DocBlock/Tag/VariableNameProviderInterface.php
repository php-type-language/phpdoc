<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

interface VariableNameProviderInterface extends TagInterface
{
    /**
     * @psalm-immutable
     * @return non-empty-string
     */
    public function getVarName(): string;
}
