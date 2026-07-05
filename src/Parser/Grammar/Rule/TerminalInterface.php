<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Rule;

/**
 * Interface denoting a leaf (that is a terminal) rule.
 */
interface TerminalInterface extends RuleInterface
{
    /**
     * Capture name under which the presence of the rule is recorded,
     * or {@see null} to match without capturing.
     *
     * @var non-empty-string|null
     */
    public ?string $alias {
        get;
    }
}
