<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;

/**
 * Reads an issue identifier, that is a run of letters, digits, underscores,
 * dashes and dots.
 *
 * @template-implements CombinatorInterface<non-empty-string>
 */
final class IssueNameCombinator implements CombinatorInterface
{
    public const NAME = 'IssueName';

    private const CHARS = 'abcdefghijklmnopqrstuvwxyz'
        . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        . '0123456789_.-';

    /**
     * @return non-empty-string
     */
    public function __invoke(Cursor $cursor): string
    {
        $name = $cursor->readWhile(self::CHARS);

        if ($name === '') {
            $cursor->fail('Expected an issue identifier');
        }

        return $name;
    }
}
