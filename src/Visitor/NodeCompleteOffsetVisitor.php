<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Visitor;

use TypeLang\Parser\Node\Node;
use TypeLang\Parser\Traverser\Command;
use TypeLang\Parser\Traverser\Visitor;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal TypeLang\PhpDocParser
 */
final class NodeCompleteOffsetVisitor extends Visitor
{
    /**
     * @var int<0, max>
     */
    public int $offset = 0;

    public function before(): void
    {
        $this->offset = 0;
    }

    public function enter(Node $node): ?Command
    {
        $this->offset = \max($this->offset, $node->offsetTo);

        return null;
    }
}
