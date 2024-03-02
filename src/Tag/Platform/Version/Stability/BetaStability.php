<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform\Version\Stability;

final class BetaStability extends Stability
{
    public function getName(): string
    {
        return 'beta';
    }

    public function getPriority(): int
    {
        return 2;
    }
}
