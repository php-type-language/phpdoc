<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

/**
 * The PhpStorm platform: the tag family understood by the PhpStorm IDE.
 */
final class PhpStormPlatform extends Platform
{
    /**
     * @var non-empty-string
     */
    public const string NAME = 'PhpStorm';

    public private(set) string $name = self::NAME;
}
