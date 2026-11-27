<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;

/**
 * Reads an email address up to its closing ">".
 *
 * @template-implements CombinatorInterface<non-empty-string>
 */
final class EmailCombinator implements CombinatorInterface
{
    public const NAME = 'Email';

    /**
     * @return non-empty-string
     */
    public function __invoke(Cursor $cursor): string
    {
        $email = \trim($cursor->readUntil('>'));

        if ($email === '') {
            $cursor->fail('Expected an email address');
        }

        return $email;
    }
}
