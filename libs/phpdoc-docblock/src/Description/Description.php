<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Description;

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
        if ($description instanceof DescriptionInterface) {
            return $description;
        }

        if ($description === null || ((string) $description) === '') {
            return null;
        }

        return new self($description);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
