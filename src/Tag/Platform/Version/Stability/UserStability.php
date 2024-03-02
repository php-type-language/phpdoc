<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform\Version\Stability;

final class UserStability extends Stability
{
    /**
     * @param non-empty-string $name
     * @param int<0, max> $revision
     */
    public function __construct(
        public readonly string $name,
        int $revision = 0,
    ) {
        parent::__construct($revision);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPriority(): int
    {
        return 5;
    }
}
