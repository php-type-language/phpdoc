<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Documentation;

use TypeLang\PhpDoc\Build\Model\Alias;
use TypeLang\PhpDoc\Build\Model\Example;
use TypeLang\PhpDoc\Build\Model\Tag;

/**
 * Renders the parts of a documentation page owned by the model: the grammar
 * of the tag and its examples.
 */
final class ReferenceRenderer
{
    /**
     * Width the title of an example is wrapped at.
     */
    private const TITLE_WIDTH = 76;

    /**
     * The grammar of the tag written under each of the names.
     *
     * @param non-empty-list<non-empty-string> $names
     */
    public function renderGrammar(Tag $tag, array $names): string
    {
        $lines = [];

        foreach ($names as $name) {
            $lines[] = \sprintf('"@%s" %s', $name, $tag->grammar);
        }

        return "```\n" . \implode("\n", $lines) . "\n```";
    }

    /**
     * The examples of the tag, each quoted under its title.
     *
     * @param list<Example> $examples
     */
    public function renderExamples(array $examples): string
    {
        $blocks = [];

        foreach ($examples as $example) {
            $lines = [];

            if ($example->title !== null) {
                \array_push($lines, ...\explode("\n", \wordwrap($example->title, self::TITLE_WIDTH)));
            }

            $lines[] = '```php';
            \array_push($lines, ...\explode("\n", $example->code));
            $lines[] = '```';

            $blocks[] = self::quote($lines);
        }

        return \implode("\n\n", $blocks);
    }

    /**
     * The examples of the alias side by side with the same examples written
     * under the canonical name.
     */
    public function renderAliasExamples(Tag $tag, Alias $alias): string
    {
        $canonical = [];

        foreach ($alias->examples as $example) {
            $canonical[] = new Example($example->title, self::rename($example->code, $alias->name, $tag->name));
        }

        return "<tabs>\n"
            . "<tab title=\"Canonical\">\n\n" . $this->renderExamples($canonical) . "\n\n</tab>\n"
            . "<tab title=\"Alias\">\n\n" . $this->renderExamples($alias->examples) . "\n\n</tab>\n"
            . '</tabs>';
    }

    /**
     * @param list<string> $lines
     */
    private static function quote(array $lines): string
    {
        $result = [];

        foreach ($lines as $line) {
            if ($line === '') {
                $result[] = '>';

                continue;
            }

            $result[] = '> ' . $line;
        }

        return \implode("\n", $result);
    }

    private static function rename(string $code, string $from, string $to): string
    {
        return (string) \preg_replace('/@' . \preg_quote($from, '/') . '(?![\w:-])/', '@' . $to, $code);
    }
}
