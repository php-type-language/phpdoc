<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

interface InvalidTagInterface extends TagInterface
{
    /**
     * Returns the reason why this tag is invalid.
     */
    public function getReason(): \Throwable;
}
