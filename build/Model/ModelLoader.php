<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;
use TypeLang\PhpDoc\Parser\Grammar\Rule\AlternationRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\LiteralRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\MatchRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\OptionalityRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RepetitionRule;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RuleInterface;
use TypeLang\PhpDoc\Parser\Grammar\Rule\SequencingRule;

/**
 * Reads the model files of a directory, each checked against the schema.
 *
 * The grammar of a tag is read into the runtime rules it is generated as.
 */
final class ModelLoader
{
    private const SCHEMA = 'phpdoc.xsd';

    /**
     * Loads every `*.xml` file of the directory, in the order of file names.
     *
     * @throws ModelException in case of a file is not a valid model file
     */
    public function load(string $directory): Model
    {
        /** @var list<non-empty-string>|false */
        $files = \glob($directory . '/*.xml');

        if ($files === false || $files === []) {
            throw ModelException::becauseFileIsInvalid($directory, 'no model files found');
        }

        \sort($files);

        $packages = $combinators = $tags = $aliases = [];

        foreach ($files as $file) {
            $root = $this->read($file, $directory . '/' . self::SCHEMA);
            $default = $this->findAttribute($root, 'package');
            $name = \basename($file);

            \assert($name !== '');

            foreach ($this->getChildren($root) as $element) {
                match ($element->localName) {
                    'package' => $packages[] = $this->readPackage($element, $name),
                    'combinator' => $combinators[] = $this->readCombinator($element, $name),
                    'tag' => $tags[] = $this->readTag($element, $default, $name),
                    'alias' => $aliases[] = $this->readAlias($element, $default, $name),
                    default => throw ModelException::becauseFileIsInvalid(
                        $name,
                        \sprintf('unexpected element <%s>', $element->localName),
                    ),
                };
            }
        }

        return new Model($packages, $combinators, $tags, $aliases);
    }

    /**
     * @param non-empty-string $file
     * @param non-empty-string $schema
     * @throws ModelException in case of the file is not a valid model file
     */
    private function read(string $file, string $schema): \DOMElement
    {
        $document = new \DOMDocument();
        $previous = \libxml_use_internal_errors(true);

        try {
            if (!$document->load($file) || !$document->schemaValidate($schema)) {
                $messages = \array_map(
                    static fn(\LibXMLError $e): string => \sprintf('line %d: %s', $e->line, \trim($e->message)),
                    \libxml_get_errors(),
                );

                $message = \implode(', ', $messages);

                if ($message === '') {
                    $message = 'not a valid model file';
                }

                throw ModelException::becauseFileIsInvalid(\basename($file), $message);
            }
        } finally {
            \libxml_clear_errors();
            \libxml_use_internal_errors($previous);
        }

        $root = $document->documentElement;

        \assert($root instanceof \DOMElement);

        return $root;
    }

    /**
     * @param non-empty-string $file
     */
    private function readPackage(\DOMElement $element, string $file): Package
    {
        return new Package(
            name: $this->getAttribute($element, 'name'),
            class: $this->getAttribute($element, 'class'),
            title: $this->getAttribute($element, 'title'),
            description: $this->getText($element, 'description'),
            file: $file,
        );
    }

    /**
     * @param non-empty-string $file
     */
    private function readCombinator(\DOMElement $element, string $file): Combinator
    {
        return new Combinator(
            name: $this->getAttribute($element, 'name'),
            class: $this->getAttribute($element, 'class'),
            package: $this->findAttribute($element, 'package'),
            isInstanceGenerated: $this->findAttribute($element, 'instance') !== 'manual',
            file: $file,
        );
    }

    /**
     * @param non-empty-string|null $default
     * @param non-empty-string $file
     */
    private function readTag(\DOMElement $element, ?string $default, string $file): Tag
    {
        $template = $this->findAttribute($element, 'template');
        $placement = $this->findAttribute($element, 'placement') ?? 'any';
        $grammar = $this->getChild($element, 'grammar');
        $parameters = [];

        return new Tag(
            name: $this->getAttribute($element, 'name'),
            class: $this->getAttribute($element, 'class'),
            extends: $this->findAttribute($element, 'extends'),
            templateArguments: $this->readTemplateArguments($template),
            isDefinitionGenerated: $this->findAttribute($element, 'definition') !== 'manual',
            placement: match ($placement) {
                'block' => TagPlacement::Block,
                'inline' => TagPlacement::Inline,
                default => TagPlacement::Any,
            },
            package: $this->findAttribute($element, 'package') ?? $default,
            summary: $this->getText($element, 'summary'),
            description: $this->findText($element, 'description') ?? '',
            grammar: $this->readRule($this->getChildren($grammar)[0], $parameters),
            parameters: $parameters,
            examples: $this->readExamples($element),
            since: $this->findAttribute($element, 'since'),
            deprecated: $this->findAttribute($element, 'deprecated'),
            file: $file,
        );
    }

    /**
     * @param non-empty-string|null $default
     * @param non-empty-string $file
     * @throws ModelException in case of the alias has no package
     */
    private function readAlias(\DOMElement $element, ?string $default, string $file): Alias
    {
        $name = $this->getAttribute($element, 'name');
        $package = $this->findAttribute($element, 'package') ?? $default;

        if ($package === null) {
            throw ModelException::becauseFileIsInvalid($file, \sprintf('alias @%s has no package', $name));
        }

        return new Alias(
            name: $name,
            target: $this->getAttribute($element, 'of'),
            package: $package,
            examples: $this->readExamples($element),
            file: $file,
        );
    }

    /**
     * @return list<non-empty-string>
     */
    private function readTemplateArguments(?string $template): array
    {
        if ($template === null) {
            return [];
        }

        $result = [];

        foreach (\explode(' ', \trim($template)) as $class) {
            if ($class !== '') {
                $result[] = $class;
            }
        }

        return $result;
    }

    /**
     * @return list<Example>
     */
    private function readExamples(\DOMElement $element): array
    {
        $result = [];

        foreach ($this->getChildren($element) as $child) {
            if ($child->localName === 'example') {
                $result[] = new Example(
                    title: $this->findAttribute($child, 'title'),
                    code: self::dedent($child->textContent),
                );
            }
        }

        return $result;
    }

    /**
     * @param array<non-empty-string, non-empty-string> $parameters
     *        receives the parameter names of the captures
     */
    private function readRule(\DOMElement $element, array &$parameters): RuleInterface
    {
        $children = [];

        foreach ($this->getChildren($element) as $child) {
            $children[] = $this->readRule($child, $parameters);
        }

        $alias = $this->findAttribute($element, 'as');
        $parameter = $this->findAttribute($element, 'parameter');

        if ($alias !== null && $parameter !== null) {
            $parameters[$alias] = $parameter;
        }

        return match ($element->localName) {
            'sequence' => new SequencingRule(...$children),
            'one-of' => new AlternationRule(...$children),
            'maybe' => new OptionalityRule($children[0]),
            'repeat' => new RepetitionRule($children[0], \max(0, (int) ($this->findAttribute($element, 'min') ?? 0))),
            'rule' => new MatchRule(
                $this->getAttribute($element, 'name'),
                $alias,
                $this->findAttribute($element, 'shown-as'),
            ),
            'literal' => new LiteralRule($this->getAttribute($element, 'value'), $alias),
            default => throw ModelException::becauseFileIsInvalid(
                '',
                \sprintf('unexpected grammar element <%s>', $element->localName),
            ),
        };
    }

    /**
     * @return list<\DOMElement>
     */
    private function getChildren(\DOMElement $element): array
    {
        $result = [];

        foreach ($element->childNodes as $child) {
            if ($child instanceof \DOMElement) {
                $result[] = $child;
            }
        }

        return $result;
    }

    private function findChild(\DOMElement $element, string $name): ?\DOMElement
    {
        foreach ($this->getChildren($element) as $child) {
            if ($child->localName === $name) {
                return $child;
            }
        }

        return null;
    }

    private function getChild(\DOMElement $element, string $name): \DOMElement
    {
        return $this->findChild($element, $name)
            ?? throw ModelException::becauseFileIsInvalid('', \sprintf('<%s> expected', $name));
    }

    private function findText(\DOMElement $element, string $name): ?string
    {
        $child = $this->findChild($element, $name);

        if ($child === null) {
            return null;
        }

        return self::dedent($child->textContent);
    }

    private function getText(\DOMElement $element, string $name): string
    {
        return self::dedent($this->getChild($element, $name)->textContent);
    }

    /**
     * @return non-empty-string|null
     */
    private function findAttribute(\DOMElement $element, string $name): ?string
    {
        $value = $element->getAttribute($name);

        if ($value === '') {
            return null;
        }

        return $value;
    }

    /**
     * @return non-empty-string
     */
    private function getAttribute(\DOMElement $element, string $name): string
    {
        return $this->findAttribute($element, $name)
            ?? throw ModelException::becauseFileIsInvalid('', \sprintf('attribute "%s" expected', $name));
    }

    /**
     * Removes the indentation the text has inside the XML, along with the
     * leading and trailing blank lines.
     */
    public static function dedent(string $text): string
    {
        $lines = \explode("\n", \str_replace("\r\n", "\n", $text));

        while ($lines !== [] && \trim($lines[0]) === '') {
            \array_shift($lines);
        }

        while ($lines !== [] && \trim($lines[\count($lines) - 1]) === '') {
            \array_pop($lines);
        }

        $indent = null;

        foreach ($lines as $line) {
            if (\trim($line) === '') {
                continue;
            }

            $current = \strlen($line) - \strlen(\ltrim($line, ' '));
            $indent = $indent === null ? $current : \min($indent, $current);
        }

        $result = [];

        foreach ($lines as $line) {
            $result[] = \rtrim(\substr($line, (int) $indent));
        }

        return \implode("\n", $result);
    }
}
