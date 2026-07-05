<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Grammar;

use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Grammar;
use TypeLang\PhpDoc\Tests\TestCase;

/**
 * @phpstan-import-type RuleType from Grammar
 */
abstract class GrammarRuleTestCase extends TestCase
{
    /**
     * @return RuleType
     */
    abstract protected function rule(): CombinatorInterface;

    protected function matchCursor(Cursor $cursor): mixed
    {
        $rule = $this->rule();

        return $rule($cursor);
    }

    protected function matchText(string $text): mixed
    {
        return $this->matchCursor(new Cursor($text));
    }
}
