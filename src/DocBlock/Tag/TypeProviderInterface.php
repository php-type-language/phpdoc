<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * Representation of any entry that contain type definition.
 */
interface TypeProviderInterface extends OptionalTypeProviderInterface
{
    /**
     * Gets an AST object of the {@see TypeStatement} type.
     */
    public TypeStatement $type { get; }
}
