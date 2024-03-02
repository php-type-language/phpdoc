<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform\Version\Stability;

final class RCStability extends Stability
{
    public function getName(): string
    {
        return 'rc';
    }

    public function getPriority(): int
    {
        return 1;
    }
}
