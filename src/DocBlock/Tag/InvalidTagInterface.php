<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * Marks all tags that were not correctly recognized by the parser or for
 * some other reason were not processed and classified.
 */
interface InvalidTagInterface extends TagInterface
{
    /**
     * Returns {@see InvalidTagInterface} invalid tag creation reason.
     */
    public function getReason(): \Throwable;
}
