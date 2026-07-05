<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Rule;

use TypeLang\PhpDoc\Exception\ParsingExceptionInterface;
use TypeLang\PhpDoc\Parser\Grammar\Context;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * The base interface of all parser rules.
 */
interface RuleInterface extends \Stringable
{
    /**
     * Matches the rule against the input, recording its captures.
     *
     * @throws NoMatchException when the rule does not apply
     * @throws ParsingExceptionInterface when the input is malformed
     */
    public function match(Context $context): void;

    public function __toString(): string;
}
