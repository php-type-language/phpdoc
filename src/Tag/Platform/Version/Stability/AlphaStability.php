<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform\Version\Stability;

final class AlphaStability extends Stability
{
    public function getName(): string
    {
        return 'alpha';
    }

    public function getPriority(): int
    {
        return 3;
    }
}
