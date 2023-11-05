<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Tests\Functional;

use TypeLang\PhpDocParser\DocBlock;
use TypeLang\PhpDocParser\DocBlock\Description;
use TypeLang\PhpDocParser\DocBlock\Tag\GenericTag;
use TypeLang\PhpDocParser\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDocParser\DocBlock\Tag\InvalidTagInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;
use TypeLang\PhpDocParser\Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected const NOT_IMPLEMENTED_TAGS = [
        // Non-Standard
        'fix',
        'fixme',
        'override',
        'note',
        'alias', // Carbon + Psalm
        'class',
        'const',
        'enum',
        'inherits', // alias for "extends"
        'private',

        // Doctrine
        'not-deprecated',

        // Laravel
        'named-arguments-supported',
        'use',

        // General Linters
        'type',
        'template',
        'template-covariant',
        'template-extends',
        'template-implements',
        'param-out',
        'mutation-free',
        'readonly',
        'unused-param',
        'seal-methods',
        'seal-properties',
        'implements',
        'extends',
        'mixin',

        // Laminas
        'configkey',

        // phpDocumentor 1.x
        'access',
        'code',
        'deprec',
        'endcode',
        'exception',
        'final',
        'ingroup',
        'magic',
        'name',
        'static',
        'staticvar',
        'toc',
        'tutorial',

        // phpDocumentor 2.x
        'index',

        // PHPUnit 9.x
        'author',
        'after',
        'afterclass',
        'backupglobals',
        'backupstaticattributes',
        'before',
        'beforeClass',
        'codecoverageignore',
        'codecoverageignorestart',
        'codecoverageignoreend',
        'covers',
        'coversdefaultclass',
        'coversnothing',
        'dataprovider',
        'depends',
        'doesnotperformassertions',
        'expectedexception',
        'expectedexceptioncode',
        'expectedexceptionmessage',
        'expectedexceptionmessageregexp',
        'group',
        'large',
        'medium',
        'preserveglobalstate',
        'requires',
        'runtestsinseparateprocesses',
        'runinseparateprocess',
        'small',
        'test',
        'testdox',
        'testwith',
        'ticket',
        'uses',

        // PlainUML
        'startuml',
        'enduml',

        // PEAR
        'package_version',

        // JetBrains
        'formatter:off',
        'formatter:on',
        'noinspection',
        'language',

        // Symfony
        'experimental',

        // PhpCsFixer
        'custom',
        'phpcs:disable',
        'phpcs:enable',

        // PhpCodeSniffer
        'codingstandardsignorestart',
        'codingstandardsignoreend',

        // SlevomatCodingStandard
        'phpcssuppress',

        // Rector
        'funccall',
        'changelog',
        'norector',
        'innerforeachreturn',
        'inspiration',
        'inspired',
        'ref',

        // PhpCheckStyle
        'suppresswarnings',

        // PHAN
        // LINK https://github.com/phan/phan/blob/5.4.2/src/Phan/Language/Element/Comment/Builder.php
        'phan-abstract',
        'phan-assert',
        'phan-assert',
        'phan-assert-false-condition',
        'phan-assert-true-condition',
        'phan-closure-scope', 'phanclosurescope',
        'phan-constructor-used-for-side-effects',
        'phan-extends',
        'phan-external-mutation-free',
        'phan-file-suppress',
        'phan-forbid-undeclared-magic-methods',
        'phan-forbid-undeclared-magic-properties',
        'phan-hardcode-return-type',
        'phan-ignore-reference',
        'phan-immutable',
        'phan-inherits',
        'phan-mandatory-param',
        'phan-method',
        'phan-mixin',
        'phan-output-reference',
        'phan-override',
        'phan-param',
        'phan-property',
        'phan-property-read',
        'phan-property-write',
        'phan-pure',
        'phan-read-only',
        'phan-real-return',
        'phan-real-throws',
        'phan-return',
        'phan-side-effect-free',
        'phan-suppress',
        'phan-suppress-current-line',
        'phan-suppress-next-line',
        'phan-suppress-next-next-line',
        'phan-suppress-previous-line',
        'phan-template',
        'phan-type',
        'phan-unused-param',
        'phan-var',
        'phan-write-only',

        // PHPSTAN
        // LINK https://github.com/phpstan/phpstan-src/blob/1.10.40/src/Rules/PhpDoc/InvalidPHPStanDocTagRule.php#L23
        'phpstan-param',
        'phpstan-param-out',
        'phpstan-var',
        'phpstan-extends',
        'phpstan-implements',
        'phpstan-use',
        'phpstan-template',
        'phpstan-template-contravariant',
        'phpstan-template-covariant',
        'phpstan-return',
        'phpstan-throws',
        'phpstan-ignore-next-line',
        'phpstan-ignore-line',
        'phpstan-method',
        'phpstan-pure',
        'phpstan-impure',
        'phpstan-immutable',
        'phpstan-type',
        'phpstan-import-type',
        'phpstan-property',
        'phpstan-property-read',
        'phpstan-property-write',
        'phpstan-consistent-constructor',
        'phpstan-assert',
        'phpstan-assert-if-true',
        'phpstan-assert-if-false',
        'phpstan-self-out',
        'phpstan-this-out',
        'phpstan-allow-private-mutation',
        'phpstan-readonly',
        'phpstan-readonly-allow-private-mutation',

        // PSALM
        // LINK https://github.com/vimeo/psalm/blob/5.15.0/src/Psalm/DocComment.php#L21
        'php-from',
        'psalm-allow-private-mutation',
        'psalm-api',
        'psalm-assert',
        'psalm-assert-if-false',
        'psalm-assert-if-true',
        'psalm-assert-untainted',
        'psalm-check-type',
        'psalm-check-type-exact',
        'psalm-consistent-constructor',
        'psalm-consistent-templates',
        'psalm-external-mutation-free',
        'psalm-flow',
        'psalm-if-this-is',
        'psalm-ignore-falsable-return',
        'psalm-ignore-nullable-return',
        'psalm-ignore-var',
        'psalm-ignore-variable-method',
        'psalm-ignore-variable-property',
        'psalm-immutable',
        'psalm-import-type',
        'psalm-inheritors',
        'psalm-internal',
        'psalm-method',
        'psalm-mutation-free',
        'psalm-no-seal-methods',
        'psalm-no-seal-properties',
        'psalm-override-method-visibility',
        'psalm-override-property-visibility',
        'psalm-param',
        'psalm-param-out',
        'psalm-property',
        'psalm-property-read',
        'psalm-property-write',
        'psalm-pure',
        'psalm-readonly',
        'psalm-readonly-allow-private-mutation',
        'psalm-require-extends',
        'psalm-require-implements',
        'psalm-return',
        'psalm-scope-this',
        'psalm-seal-methods',
        'psalm-seal-properties',
        'psalm-self-out',
        'psalm-stub-override',
        'psalm-suppress',
        'psalm-taint-escape',
        'psalm-taint-sink',
        'psalm-taint-source',
        'psalm-taint-specialize',
        'psalm-taint-unescape',
        'psalm-template',
        'psalm-template-covariant',
        'psalm-this-out',
        'psalm-trace',
        'psalm-type',
        'psalm-var',
        'psalm-variadic',
        'psalm-yield',
    ];

    /**
     * @param list<non-empty-string> $except
     */
    protected function assertDocBlockNotContainsInvalidTags(
        DocBlock $docBlock,
        array $except = [],
    ): void {
        $this->assertDescriptionNotContainsInvalidTags($docBlock->getDescription(), $except);
        $this->assertTagsNotContainsInvalidTags($docBlock->getTags(), $except);
    }

    /**
     * @param list<non-empty-string> $except
     */
    protected function assertDescriptionNotContainsInvalidTags(
        \Stringable|string|null $description,
        array $except = [],
    ): void {
        if ($description instanceof Description) {
            $this->assertTagsNotContainsInvalidTags($description->getTags(), $except);
        }
    }

    /**
     * @param iterable<TagInterface> $tags
     * @param list<non-empty-string> $except
     */
    protected function assertTagsNotContainsInvalidTags(iterable $tags, array $except = []): void
    {
        // Suppress "no assertions" notice
        self::assertTrue(true);

        foreach ($tags as $tag) {
            if ($tag instanceof InvalidTagInterface) {
                if (!$this->isSkipAllowed($tag)) {
                    self::assertContains($tag->getName(), $except);
                }
            }

            $this->assertDescriptionNotContainsInvalidTags($tag->getDescription(), $except);
        }
    }

    protected function isSkipAllowed(InvalidTagInterface $tag): bool
    {
        $description = $tag->getDescription();

        if (!$description instanceof Description) {
            return false;
        }

        // Skip "A is B ? C : D" expressions.
        return \str_contains($description->getBodyTemplate(), ' is ')
            && \str_contains($description->getBodyTemplate(), ' ? ')
            && \str_contains($description->getBodyTemplate(), ' : ');
    }
}
