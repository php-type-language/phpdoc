<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Shared\Version;

final class Stability implements \Stringable
{
    /**
     * @var int<0, max>
     */
    public readonly int $priority;

    public function __construct(
        public readonly StabilityType $type,
        /**
         * @var int<0, max>
         */
        public readonly int $version = 0,
    ) {
        // @phpstan-ignore-next-line : This expression provides int<0, max>
        $this->priority = $this->version << 2 | $this->type->value;
    }

    /**
     * @param int<0, max> $version
     */
    public static function rc(int $version = 0): self
    {
        return new self(StabilityType::ReleaseCandidate, $version);
    }

    /**
     * @param int<0, max> $version
     */
    public static function beta(int $version = 0): self
    {
        return new self(StabilityType::Beta, $version);
    }

    /**
     * @param int<0, max> $version
     */
    public static function alpha(int $version = 0): self
    {
        return new self(StabilityType::Alpha, $version);
    }

    /**
     * @param int<0, max> $version
     */
    public static function dev(int $version = 0): self
    {
        return new self(StabilityType::Dev, $version);
    }

    public function compare(self $other): int
    {
        return $this->priority <=> $other->priority;
    }

    public function __toString(): string
    {
        return match ($this->type) {
            StabilityType::ReleaseCandidate => 'RC' . $this->version,
            StabilityType::Beta => 'beta' . $this->version,
            StabilityType::Alpha => 'alpha' . $this->version,
            StabilityType::Dev => 'dev' . $this->version,
        };
    }
}
