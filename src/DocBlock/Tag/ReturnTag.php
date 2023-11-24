<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

final class ReturnTag extends TypedTag implements CreatableFromTagAndDescriptionInterface
{
    public function __construct(TypeStatement $type, \Stringable|string|null $description = null)
    {
        parent::__construct('return', $type, $description);
    }

    public static function createFromTagAndDescription(
        TypeStatement $type,
        \Stringable|string|null $description = null,
    ): CreatableFromTagAndDescriptionInterface {
        return new self($type, $description);
    }
}
