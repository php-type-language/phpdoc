<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Description;

class Description implements DescriptionInterface
{
    protected readonly string $value;

    public function __construct(string|\Stringable $value = '')
    {
        $this->value = (string) $value;
    }

    /**
     * @return ($description is DescriptionInterface ? DescriptionInterface : self)
     */
    public static function fromStringable(string|\Stringable $description): DescriptionInterface
    {
        if ($description instanceof DescriptionInterface) {
            return $description;
        }

        return new self($description);
    }

    /**
     * @return ($description is DescriptionInterface ? DescriptionInterface : self|null)
     */
    public static function fromStringableOrNull(string|\Stringable|null $description): ?DescriptionInterface
    {
        if ($description === null) {
            return null;
        }

        return self::fromStringable($description);
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
