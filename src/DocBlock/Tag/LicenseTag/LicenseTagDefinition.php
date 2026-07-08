<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\LicenseTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\UrlReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@license` tag documents the license that applies to an element, given
 * either as a URL to the license text or by its name.
 *
 * ```
 * "@license" <URL> [ <Description> ]
 * "@license" <Description>
 * ```
 */
final class LicenseTagDefinition extends TagDefinition
{
    public const string NAME = 'license';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::oneOf(
                Spec::sequence(
                    Spec::rule(UrlCombinator::NAME, 'url'),
                    Spec::maybe(Spec::rule(DescriptionCombinator::NAME, 'description')),
                ),
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): LicenseTag
    {
        /** @var UrlReference|null $url */
        $url = $result->find('url');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new LicenseTag($name, $url, $description);
    }
}
