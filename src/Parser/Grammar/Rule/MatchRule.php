<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Rule;

use TypeLang\PhpDoc\Parser\Grammar\Context;
use TypeLang\PhpDoc\Parser\Grammar\Grammar;

/**
 * Matches a named terminal from the {@see Grammar} (`URI`, a type, a variable,
 * ...) and captures its value under an alias.
 *
 * ```
 * new MatchRule('URI', 'uri'); // captures the value as "uri"
 * new MatchRule('URI');        // matches but captures nothing
 * ```
 */
final class MatchRule implements TerminalInterface
{
    public function __construct(
        /**
         * Name of a terminal from the grammar.
         *
         * @var non-empty-string
         */
        public readonly string $rule,
        /**
         * Capture name of the matched value, {@see null} to capture nothing.
         *
         * @var non-empty-string|null
         */
        public readonly ?string $alias = null,
        /**
         * Name shown in place of {@see $rule} in the rendered grammar.
         *
         * @var non-empty-string|null
         */
        public readonly ?string $renderAs = null,
    ) {}

    public function match(Context $context): void
    {
        $rule = $context->grammar->get($this->rule);

        $value = $rule($context->cursor);

        if ($this->alias !== null) {
            $context->capture($this->alias, $value);
        }
    }

    public function __toString(): string
    {
        return \sprintf('<%s>', $this->renderAs ?? $this->rule);
    }
}
