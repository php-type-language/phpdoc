<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Reads an issue identifier, that is a run of letters, digits, underscores,
 * dashes and dots.
 *
 * @implements CombinatorInterface<non-empty-string>
 */
final readonly class IssueNameCombinator implements CombinatorInterface
{
    public const string NAME = 'IssueName';

    private const string CHARS = 'abcdefghijklmnopqrstuvwxyz'
        . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        . '0123456789_.-';

    /**
     * @return non-empty-string
     */
    public function __invoke(Cursor $cursor): string
    {
        $name = $cursor->readWhile(self::CHARS);

        if ($name === '') {
            throw new NoMatchException('Expected an issue identifier');
        }

        return $name;
    }
}
