<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmFlowTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlowType;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;

/**
 * The `@psalm-flow` tag describes how tainted data flows through a function,
 * optionally naming the parameter the flow applies to.
 */
final class PsalmFlowTag extends Tag
{
    public function __construct(
        string $name,
        /**
         * The kind of taint flow being described.
         */
        public readonly FlowType $flow,
        /**
         * The parameter the flow applies to, when named.
         *
         * @var non-empty-string|null
         */
        public readonly ?string $variable = null,
    ) {
        parent::__construct($name);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s %s', $this->name, $this->flow->value);

        if ($this->variable !== null) {
            $result .= ' $' . $this->variable;
        }

        return $result;
    }
}
