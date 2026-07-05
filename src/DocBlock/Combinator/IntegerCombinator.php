<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Reads a non-negative integer.
 *
 * @implements CombinatorInterface<int<0, max>>
 */
final readonly class IntegerCombinator implements CombinatorInterface
{
    public const string NAME = 'Integer';

    private const string DIGITS = '0123456789';

    /**
     * @return int<0, max>
     */
    public function __invoke(Cursor $cursor): int
    {
        $digits = $cursor->readWhile(self::DIGITS);

        if ($digits === '') {
            throw new NoMatchException('Expected an integer');
        }

        // The run consists solely of digits, so the value is never negative.
        return \max(0, (int) $digits);
    }
}
