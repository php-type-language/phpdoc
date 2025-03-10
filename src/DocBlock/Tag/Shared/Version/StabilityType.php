<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Shared\Version;

enum StabilityType: int
{
    case Dev = 0x0000;
    case Alpha = 0x0001;
    case Beta = 0x0002;
    case ReleaseCandidate = 0x0003;
}
