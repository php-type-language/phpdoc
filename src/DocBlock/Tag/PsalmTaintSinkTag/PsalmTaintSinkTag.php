<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintSinkTag;

use TypeLang\PhpDoc\DocBlock\Tag\Tag;

/**
 * The `@psalm-taint-sink` tag marks the given variable as a sink where tainted
 * data of the named taint type must never arrive.
 */
final class PsalmTaintSinkTag extends Tag
{
    public function __construct(
        string $name,
        /**
         * The taint type guarded at this sink.
         *
         * @var non-empty-string
         */
        public readonly string $taint,
        /**
         * The variable acting as the taint sink.
         *
         * @var non-empty-string
         */
        public readonly string $variable,
    ) {
        parent::__construct($name);
    }

    #[\Override]
    public function __toString(): string
    {
        return \sprintf('@%s %s $%s', $this->name, $this->taint, $this->variable);
    }
}
