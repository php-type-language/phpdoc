<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform\Version\Stability;

abstract class Stability implements StabilityInterface
{
    /**
     * @param int<0, max> $revision
     */
    public function __construct(
        protected readonly int $revision = 0,
    ) {}

    public static function parse(string|\Stringable $stability): self
    {
        \preg_match('/^([a-z]+)(\d+)?$/', (string) $stability, $matches);

        $stability = \strtolower($matches[1] ?? '');
        $revision = \max((int) ($matches[2] ?? 0), 0);

        return match ($stability) {
            'dev' => DevStability::getInstance(),
            'alpha' => new AlphaStability($revision),
            'beta' => new BetaStability($revision),
            'rc' => new RCStability($revision),
            '', 'stable' => Stable::getInstance(),
            default => new UserStability($stability, $revision),
        };
    }

    public function getRevision(): int
    {
        return $this->revision;
    }

    public function compare(StabilityInterface $stability): int
    {
        return $stability->getPriority() <=> $this->getPriority()
            ?: $this->getRevision() <=> $stability->getRevision();
    }

    /**
     * @return array{
     *     stability: non-empty-string,
     *     revision?: int<0, max>
     * }
     */
    public function toArray(): array
    {
        $result = ['stability' => $this->getName()];

        if ($this->getRevision() <= 0) {
            return $result;
        }

        return [
            ...$result,
            'revision' => $this->getRevision(),
        ];
    }

    /**
     * @return array{
     *     stability: non-empty-string,
     *     revision?: int<0, max>
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        if ($this->revision <= 0) {
            return $this->getName();
        }

        /** @var non-empty-string */
        return \vsprintf('%s%d', [
            $this->getName(),
            $this->getRevision(),
        ]);
    }
}
