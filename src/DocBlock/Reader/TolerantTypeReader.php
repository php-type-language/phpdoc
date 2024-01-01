<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Reader;

use TypeLang\Parser\Node\Name;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * @template-extends Reader<TypeStatement>
 */
final class TolerantTypeReader extends Reader
{
    private static ?Sequence $mixed = null;

    public function __construct(
        private readonly OptionalTypeReader $reader,
    ) {
        self::$mixed ??= new Sequence(new NamedTypeNode(
            name: new Name('mixed'),
        ));
    }

    public function read(string $content): Sequence
    {
        return $this->reader->read($content) ?? self::$mixed;
    }
}
