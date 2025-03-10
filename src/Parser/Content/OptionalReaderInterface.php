<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

/**
 * @template-covariant T of mixed
 */
interface OptionalReaderInterface
{
    /**
     * @return T|null
     */
    public function __invoke(Stream $stream): mixed;
}
