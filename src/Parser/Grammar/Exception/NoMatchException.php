<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Exception;

/**
 * Signals that a rule did not apply and an alternative may be tried.
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\PhpDoc\Parser\Grammar
 */
final class NoMatchException extends GrammarException {}
