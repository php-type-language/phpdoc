<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Shared;

use TypeLang\PHPDoc\DocBlock\Tag\Shared\Version\Stability;

final class Version implements \Stringable
{
    public function __construct(
        /**
         * @var int<0, max>
         */
        public readonly int $major = 0,
        /**
         * @var int<0, max>
         */
        public readonly int $minor = 0,
        /**
         * @var int<0, max>|null
         */
        public readonly ?int $patch = null,
        /**
         * @var int<0, max>|null
         */
        public readonly ?int $build = null,
        public readonly ?Stability $stability = null,
    ) {}

    /**
     * @return non-empty-string
     */
    private function toSignificantVersionString(): string
    {
        return \sprintf('%d.%d.%d.%d', $this->major, $this->minor, $this->patch, $this->build);
    }

    public function compare(self $other): int
    {
        $result = \version_compare(
            version1: $this->toSignificantVersionString(),
            version2: $other->toSignificantVersionString(),
        );

        if ($result === 0) {
            // Compare stability if provided
            if ($this->stability !== null && $other->stability !== null) {
                return $this->stability->compare($other->stability);
            }

            // Stable (no Stability flag) is greater than RC/beta/alpha/dev/etc
            return $this->stability === null ? 1 : -1;
        }

        return $result;
    }

    public function __toString(): string
    {
        $result = [$this->major, $this->minor];

        foreach ([$this->patch, $this->build] as $insignificant) {
            if ($insignificant === null) {
                break;
            }

            $result[] = $insignificant;
        }

        $version = \implode('.', $result);

        if ($this->stability !== null) {
            $version .= '-' . $this->stability;
        }

        return $version;
    }
}
