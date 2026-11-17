<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Internal;

use TypeLang\PhpDoc\Platform\PhanPlatform;
use TypeLang\PhpDoc\Platform\PhpCodeSnifferPlatform;
use TypeLang\PhpDoc\Platform\PhpDocumentorPlatform;
use TypeLang\PhpDoc\Platform\PhpStanPlatform;
use TypeLang\PhpDoc\Platform\PhpStormPlatform;
use TypeLang\PhpDoc\Platform\PlatformInterface;
use TypeLang\PhpDoc\Platform\PsalmPlatform;
use TypeLang\PhpDoc\Platform\StandardPlatform;

/**
 * Every platform the library ships, in no particular order: no name is
 * registered by two of them.
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\PhpDoc
 *
 * @generated from resources/model/*.xml, edit the model instead
 */
final class BuiltinPlatforms
{
    /**
     * @return non-empty-list<PlatformInterface>
     */
    public static function create(): array
    {
        return [
            new PhanPlatform(),
            new PhpCodeSnifferPlatform(),
            new PhpDocumentorPlatform(),
            new PhpStanPlatform(),
            new PhpStormPlatform(),
            new PsalmPlatform(),
            new StandardPlatform(),
        ];
    }
}
