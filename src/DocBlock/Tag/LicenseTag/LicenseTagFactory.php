<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\LicenseTag;

use Composer\Spdx\SpdxLicenses;
use League\Uri\Uri;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\UriReference;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Content\UriReferenceReader;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@license`" tags.
 *
 * See {@see LicenseTag} for details about this tag.
 */
final class LicenseTagFactory implements TagFactoryInterface
{
    private readonly ?SpdxLicenses $licenses;

    public function __construct()
    {
        $this->licenses = $this->createSpdxLicenses();
    }

    private function createSpdxLicenses(): ?SpdxLicenses
    {
        if (\class_exists(SpdxLicenses::class)) {
            return new SpdxLicenses();
        }

        return null;
    }

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): LicenseTag
    {
        $stream = new Stream($tag, $content);

        try {
            $uri = $stream->apply(new UriReferenceReader());
        } catch (\Throwable) {
            $uri = null;
        }

        $description = $identifier = ($suffix = \trim($stream->value)) === '' ? null : $suffix;

        if ($this->licenses !== null && $identifier !== null) {
            /** @var array{0:non-empty-string, 1:bool, 2:non-empty-string, 3:bool}|null $info */
            $info = $this->licenses->getLicenseByIdentifier($identifier);

            if ($info !== null) {
                $uri ??= new UriReference(Uri::new($info[2]));
                $description = $info[0];
            }
        }

        return new LicenseTag(
            name: $tag,
            uri: $uri,
            license: $identifier,
            description: $description,
        );
    }
}
