<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Rule;

/**
 * Interface denoting a leaf (that is a terminal) rule.
 *
 * @property-read ?non-empty-string $alias The name the matched value is captured under, if any.
 */
interface TerminalInterface extends RuleInterface {}
