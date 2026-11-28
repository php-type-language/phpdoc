<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Generator;

/**
 * Renders the frame of a generated PHP file: the header, the namespace, the
 * imports and a docblock.
 */
final class PhpFile
{
    /**
     * The docblock tag marking a generated file, the generator replaces and
     * removes only the files carrying it.
     */
    public const MARKER = '@generated';

    /**
     * @param non-empty-string $namespace
     * @param string $docblock text of the class docblock, without the marker
     * @param non-empty-string $source model file the class is generated from
     * @param string $body the class declaration
     */
    public static function render(
        string $namespace,
        Imports $imports,
        string $docblock,
        string $source,
        string $body,
    ): string {
        $text = self::escapeDirectives(\rtrim($docblock)) . "\n\n" . self::MARKER . ' from resources/model/' . $source
            . ', edit the model instead';

        $uses = $imports->render();

        if ($uses !== '') {
            $uses .= "\n";
        }

        return "<?php\n\ndeclare(strict_types=1);\n\n"
            . 'namespace ' . $namespace . ";\n\n"
            . $uses
            . self::renderDocblock($text, 0)
            . $body;
    }

    /**
     * Breaks the mentions of the tags that analyzers and IDEs act on wherever
     * they meet them in a comment (`deprecated`, `phpstan-ignore` and others)
     * by a zero-width space after the `@`. An inline tag (`{@see ...}`) is
     * left as is.
     */
    public static function escapeDirectives(string $text): string
    {
        return (string) \preg_replace(
            '/(?<![{\w])@(?=deprecated|phpstan-ignore|psalm-suppress|phan-(?:file-)?suppress'
                . '|codingStandardsIgnore|phpcs|noinspection|formatter:)/',
            "@\u{200B}",
            $text,
        );
    }

    /**
     * Renders the text as a docblock, a blank line of the text becomes an
     * empty docblock line.
     */
    public static function renderDocblock(string $text, int $indent): string
    {
        $pad = \str_repeat(' ', $indent);
        $result = $pad . "/**\n";

        foreach (\explode("\n", $text) as $line) {
            if ($line === '') {
                $result .= $pad . " *\n";

                continue;
            }

            $result .= $pad . ' * ' . $line . "\n";
        }

        return $result . $pad . " */\n";
    }

    /**
     * Renders the value as a single-quoted PHP string.
     */
    public static function renderString(string $value): string
    {
        return "'" . \addcslashes($value, "'\\") . "'";
    }

    /**
     * Renders the text as a nowdoc string, each line and the closing marker
     * indented by the given number of spaces.
     *
     * @throws \LogicException in case of a line of the text starts with the
     *         marker and would close the nowdoc
     */
    public static function renderNowdoc(string $text, int $indent): string
    {
        $pad = \str_repeat(' ', $indent);
        $result = "<<<'TEXT'\n";

        foreach (\explode("\n", $text) as $line) {
            if (\preg_match('/^\s*TEXT(?![A-Za-z0-9_\x80-\xFF])/', $line) === 1) {
                throw new \LogicException('The text has a line closing the nowdoc');
            }

            if ($line !== '') {
                $result .= $pad . $line;
            }

            $result .= "\n";
        }

        return $result . $pad . 'TEXT';
    }

    /**
     * Whether the file was produced by the generator.
     */
    public static function isGenerated(string $contents): bool
    {
        return \str_contains($contents, ' * ' . self::MARKER . ' from resources/model/');
    }
}
