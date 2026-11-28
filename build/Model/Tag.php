<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RuleInterface;

/**
 * A tag declared under its canonical name.
 *
 * The definition class is the tag class with the `Definition` suffix. Both
 * are generated unless the model says otherwise: the tag class is written by
 * hand when no base class is given, the definition when it is marked manual.
 */
final class Tag
{
    public function __construct(
        /**
         * Canonical name of the tag, without the leading `@`.
         *
         * @var non-empty-string
         */
        public readonly string $name,
        /**
         * Fully qualified name of the tag class.
         *
         * @var non-empty-string
         */
        public readonly string $class,
        /**
         * Fully qualified name of the base class of a generated tag class,
         * {@see null} when the tag class is written by hand.
         *
         * @var non-empty-string|null
         */
        public readonly ?string $extends,
        /**
         * Type arguments of the base class (`@template-extends`), fully
         * qualified class names.
         *
         * @var list<non-empty-string>
         */
        public readonly array $templateArguments,
        /**
         * Whether the definition class is generated.
         */
        public readonly bool $isDefinitionGenerated,
        public readonly TagPlacement $placement,
        /**
         * Package registering the tag under its canonical name, {@see null}
         * for a tag registered only under the names of its aliases.
         *
         * @var non-empty-string|null
         */
        public readonly ?string $package,
        /**
         * One sentence: the docblock of the tag class and the first paragraph
         * of the docblock and of the description of the definition class.
         *
         * @var non-empty-string
         */
        public readonly string $summary,
        /**
         * Paragraphs of the docblock and of the description of the
         * definition class following the summary, as written in the model.
         */
        public readonly string $description,
        /**
         * Body of the tag.
         */
        public readonly RuleInterface $grammar,
        /**
         * Names of the constructor parameters of the tag class for the
         * captures not named after them.
         *
         * @var array<non-empty-string, non-empty-string>
         */
        public readonly array $parameters,
        /**
         * @var list<Example>
         */
        public readonly array $examples,
        /**
         * Version of the tool the tag appeared in.
         *
         * @var non-empty-string|null
         */
        public readonly ?string $since,
        /**
         * Version of the tool the tag is deprecated since.
         *
         * @var non-empty-string|null
         */
        public readonly ?string $deprecated,
        /**
         * Model file the tag is declared in.
         *
         * @var non-empty-string
         */
        public readonly string $file,
    ) {}

    /**
     * Fully qualified name of the definition class.
     *
     * @return non-empty-string
     */
    public function getDefinitionClass(): string
    {
        return $this->class . 'Definition';
    }

    /**
     * Name of the constructor parameter of the tag class receiving the
     * capture.
     *
     * @param non-empty-string $capture
     * @return non-empty-string
     */
    public function getParameterName(string $capture): string
    {
        if (isset($this->parameters[$capture])) {
            return $this->parameters[$capture];
        }

        $words = \explode('-', $capture);
        $result = \array_shift($words);

        foreach ($words as $word) {
            $result .= \ucfirst($word);
        }

        \assert($result !== '');

        return $result;
    }
}
