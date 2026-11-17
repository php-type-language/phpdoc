<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Documentation;

use TypeLang\PhpDoc\Build\Model\Alias;
use TypeLang\PhpDoc\Build\Model\LinkedModel;
use TypeLang\PhpDoc\Build\Model\Tag;

/**
 * Brings the tag pages of the documentation in line with the model.
 *
 * Every registered name (a tag of a package or an alias) has a page, found by
 * its `# @name` heading. The generated regions of an existing page are
 * rewritten, a missing page is created with the summary and the regions.
 */
final class DocumentationGenerator
{
    private readonly ReferenceRenderer $renderer;

    public function __construct()
    {
        $this->renderer = new ReferenceRenderer();
    }

    /**
     * Contents of the pages that differ from the directory, by file name.
     *
     * @return array<non-empty-string, string>
     */
    public function generate(LinkedModel $model, string $directory): array
    {
        $pages = $this->indexPages($directory);
        $result = [];

        foreach ($this->getNames($model) as $name => [$tag, $alias, $isRuntime]) {
            $file = $pages[\strtolower($name)] ?? null;

            if ($file === null) {
                $result[self::getFileName($name)] = $this->createPage($name, $tag, $alias, $isRuntime);

                continue;
            }

            $existing = (string) \file_get_contents($directory . '/' . $file);
            $updated = $this->updatePage(new Page($existing), $tag, $alias, $isRuntime)->contents;

            if ($updated !== $existing) {
                $result[$file] = $updated;
            }
        }

        \ksort($result, \SORT_STRING);

        return $result;
    }

    /**
     * Every registered name with the tag it ends up at, the alias it is
     * declared by and whether the alias is a runtime one (in the package of
     * its target), in the order of the names.
     *
     * @return array<non-empty-string, array{Tag, Alias|null, bool}>
     */
    private function getNames(LinkedModel $model): array
    {
        $result = [];

        foreach ($model->tags as $tag) {
            if ($tag->package !== null) {
                $result[$tag->name] = [$tag, null, false];
            }
        }

        foreach ($model->model->aliases as $alias) {
            $linked = $model->packages[$alias->package];
            $tag = $linked->tags[$alias->name] ?? null;
            $target = $alias->target;

            // A runtime alias, possibly through other aliases of the package.
            while ($tag === null && $target !== null) {
                $tag = $linked->tags[$target] ?? $model->tags[$target] ?? null;
                $target = $linked->aliases[$target] ?? null;
            }

            if ($tag === null) {
                throw new \LogicException(\sprintf('Alias @%s does not lead to a tag', $alias->name));
            }

            $result[$alias->name] = [$tag, $alias, isset($linked->aliases[$alias->name])];
        }

        \ksort($result, \SORT_STRING | \SORT_FLAG_CASE);

        return $result;
    }

    private function updatePage(Page $page, Tag $tag, ?Alias $alias, bool $isRuntime): Page
    {
        if ($page->hasRegion(Page::GRAMMAR)) {
            $page = $page->withRegion(Page::GRAMMAR, $this->renderGrammar($tag, $alias, $isRuntime));
        }

        if ($page->hasRegion(Page::EXAMPLES)) {
            $page = $page->withRegion(Page::EXAMPLES, $this->renderExamples($tag, $alias, $isRuntime));
        }

        return $page;
    }

    /**
     * The grammar under the name of the page, preceded by the grammar under
     * the canonical name for a runtime alias.
     */
    private function renderGrammar(Tag $tag, ?Alias $alias, bool $isRuntime): string
    {
        if ($alias === null) {
            return $this->renderer->renderGrammar($tag, [$tag->name]);
        }

        if ($isRuntime) {
            return $this->renderer->renderGrammar($tag, [$tag->name, $alias->name]);
        }

        return $this->renderer->renderGrammar($tag, [$alias->name]);
    }

    /**
     * The examples of the page, side by side with the canonical spelling for
     * a runtime alias.
     */
    private function renderExamples(Tag $tag, ?Alias $alias, bool $isRuntime): string
    {
        if ($alias === null) {
            return $this->renderer->renderExamples($tag->examples);
        }

        if ($isRuntime) {
            return $this->renderer->renderAliasExamples($tag, $alias);
        }

        return $this->renderer->renderExamples($alias->examples);
    }

    /**
     * @param non-empty-string $name
     */
    private function createPage(string $name, Tag $tag, ?Alias $alias, bool $isRuntime): string
    {
        $lines = ['# @' . $name, '', '<primary-label ref="phpdoc-component"/>'];

        if ($alias !== null) {
            $lines[] = '<secondary-label ref="alias-tag"/>';
        }

        $lines[] = '';

        if ($alias === null) {
            $lines[] = $tag->summary;
        } else {
            $lines[] = \sprintf(
                '`@%s` is a recognized alias for [@%s](%s): the two spellings parse identically.',
                $name,
                $tag->name,
                self::getFileName($tag->name),
            );
        }

        $lines[] = '';
        $lines[] = Page::wrap(Page::GRAMMAR, $this->renderGrammar($tag, $alias, $isRuntime));

        $examples = $tag->examples;

        if ($alias !== null) {
            $examples = $alias->examples;
        }

        if ($examples !== []) {
            $lines[] = '';
            $lines[] = Page::wrap(Page::EXAMPLES, $this->renderExamples($tag, $alias, $isRuntime));
        }

        return \implode("\n", $lines) . "\n";
    }

    /**
     * File names of the tag pages by the lower-case tag name of the heading.
     *
     * @return array<non-empty-lowercase-string, non-empty-string>
     */
    private function indexPages(string $directory): array
    {
        $result = [];
        $pathnames = \glob($directory . '/*.md');

        if ($pathnames === false) {
            return [];
        }

        foreach ($pathnames as $pathname) {
            $heading = \strtok((string) \file_get_contents($pathname), "\n");

            if (\is_string($heading) && \preg_match('/^# @(\S+)/', $heading, $matches) === 1) {
                $file = \basename($pathname);

                \assert($file !== '');

                $result[\strtolower($matches[1])] = $file;
            }
        }

        return $result;
    }

    /**
     * Page file name of the tag: `codingStandardsIgnoreEnd` is described by
     * `coding-standards-ignore-end-tag.md`.
     *
     * @param non-empty-string $name
     * @return non-empty-string
     */
    public static function getFileName(string $name): string
    {
        $kebab = \strtolower((string) \preg_replace(['/([a-z0-9])([A-Z])/', '/[^A-Za-z0-9]+/'], ['$1-$2', '-'], $name));

        return $kebab . '-tag.md';
    }
}
