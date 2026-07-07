<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar;

/**
 * @phpstan-type CombinatorType (callable(Cursor): mixed)
 *
 * @template-covariant TResult of mixed = mixed
 */
interface CombinatorInterface
{
    /**
     * @return TResult
     */
    public function __invoke(Cursor $cursor): mixed;
}
