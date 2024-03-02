<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform\Version\Stability;

final class DevStability extends Stability
{
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getName(): string
    {
        return 'dev';
    }

    public function getPriority(): int
    {
        return 4;
    }
}
