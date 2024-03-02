<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform\Version\Stability;

use TypeLang\Parser\Node\SerializableInterface;

interface StabilityInterface extends SerializableInterface, \Stringable
{
    /**
     * @return int<-1, 1>
     */
    public function compare(StabilityInterface $stability): int;

    /**
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * @return int<0, max>
     */
    public function getPriority(): int;

    /**
     * @return int<0, max>
     */
    public function getRevision(): int;

    /**
     * @return non-empty-string
     */
    public function __toString(): string;
}
