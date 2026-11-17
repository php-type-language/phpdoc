<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Documentation;

/**
 * A documentation page with the regions owned by the generator, each one
 * enclosed in a pair of comments:
 *
 * ```
 * <!-- generated:grammar -->
 * ...
 * <!-- /generated:grammar -->
 * ```
 *
 * Everything outside of the regions is written by hand and kept as is.
 */
final class Page
{
    public const GRAMMAR = 'grammar';

    public const EXAMPLES = 'examples';

    public function __construct(
        public readonly string $contents,
    ) {}

    /**
     * Whether the page contains the region.
     *
     * @param non-empty-string $region
     */
    public function hasRegion(string $region): bool
    {
        return \str_contains($this->contents, self::open($region))
            && \str_contains($this->contents, self::close($region));
    }

    /**
     * The page with the contents of the region replaced.
     *
     * @param non-empty-string $region
     */
    public function withRegion(string $region, string $contents): self
    {
        $pattern = '/' . \preg_quote(self::open($region), '/') . '.*?' . \preg_quote(self::close($region), '/') . '/s';

        $result = \preg_replace_callback(
            $pattern,
            static fn(): string => self::wrap($region, $contents),
            $this->contents,
        );

        return new self((string) $result);
    }

    /**
     * The contents enclosed in the comments of the region.
     *
     * @param non-empty-string $region
     */
    public static function wrap(string $region, string $contents): string
    {
        return self::open($region) . "\n" . $contents . "\n" . self::close($region);
    }

    /**
     * @param non-empty-string $region
     */
    private static function open(string $region): string
    {
        return '<!-- generated:' . $region . ' -->';
    }

    /**
     * @param non-empty-string $region
     */
    private static function close(string $region): string
    {
        return '<!-- /generated:' . $region . ' -->';
    }
}
