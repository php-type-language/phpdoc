<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * Representation of any entry that MAY contain optional type definition.
 */
interface OptionalTypeProviderInterface
{
    /**
     * Gets an AST object of the {@see TypeStatement} type or {@see null}
     * in case the type is not specified.
     *
     * @readonly
     */
    public ?TypeStatement $type { get; }
}
