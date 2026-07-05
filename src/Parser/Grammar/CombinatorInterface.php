<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar;

/**
 * @template-covariant TResult of mixed = mixed
 */
interface CombinatorInterface
{
    /**
     * @return TResult
     */
    public function __invoke(Cursor $cursor): mixed;
}
