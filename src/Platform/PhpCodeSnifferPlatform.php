<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

/**
 * The PHP CodeSniffer platform: the "@phpcs:*" tag family understood by PHP
 * CodeSniffer.
 */
final class PhpCodeSnifferPlatform extends Platform
{
    /**
     * @var non-empty-string
     */
    public const string NAME = 'PHP CodeSniffer';

    public private(set) string $name = self::NAME;
}
