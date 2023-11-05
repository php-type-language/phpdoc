<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\ParserInterface;
use TypeLang\PhpDocParser\DocBlock\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Extractor\TagVariableExtractor;
use TypeLang\PhpDocParser\Exception\InvalidTagException;
use TypeLang\PhpDocParser\Exception\InvalidTagVariableNameException;

/**
 * @template TTag of TypedTag
 * @template-extends TypedTagFactory<TTag>
 */
final class CommonTypedTagWithNameFactory extends TypedTagFactory
{
    protected readonly TagVariableExtractor $variables;

    /**
     * @param class-string<TTag> $class
     */
    public function __construct(
        private readonly string $class,
        ParserInterface $parser,
        DescriptionFactoryInterface $descriptions,
    ) {
        $this->variables = new TagVariableExtractor();

        parent::__construct($parser, $descriptions);
    }

    public function create(string $tag): TypedTag
    {
        [$type, $description] = $this->types->extractTypeOrMixed($tag);

        try {
            [$variable, $description] = $this->variables->extractOrFail($description);
        } catch (InvalidTagVariableNameException $e) {
            $offset = $this->types->getTypeOffset($type);

            throw InvalidTagVariableNameException::fromTyped($type, $offset, $e);
        }

        try {
            /** @psalm-suppress UnsafeInstantiation */
            return new ($this->class)(
                variable: $variable,
                type: $type,
                description: $this->extractDescription($description),
            );
        } catch (\Throwable $e) {
            throw InvalidTagException::fromException($e);
        }
    }
}
