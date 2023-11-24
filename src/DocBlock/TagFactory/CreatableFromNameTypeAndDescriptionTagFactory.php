<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\TagFactory;

use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Extractor\TagVariableExtractor;
use TypeLang\PhpDocParser\DocBlock\Tag\CreatableFromNameTypeAndDescriptionInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\TypedTag;
use TypeLang\PhpDocParser\Exception\InvalidTagException;
use TypeLang\PhpDocParser\Exception\InvalidTagVariableNameException;

/**
 * @template TTag of CreatableFromNameTypeAndDescriptionInterface
 * @template-extends TypedTagFactory<TTag>
 */
final class CreatableFromNameTypeAndDescriptionTagFactory extends TypedTagFactory
{
    protected readonly TagVariableExtractor $variables;

    /**
     * @param class-string<TTag> $class
     */
    public function __construct(
        private readonly string $class,
        private readonly Parser $parser,
        ?DescriptionFactoryInterface $descriptions = null,
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
            $offset = $this->parser->lastProcessedTokenOffset;

            throw InvalidTagVariableNameException::fromTyped($type, $offset, $e);
        }

        try {
            return $this->class::createFromNameTypeAndDescription(
                name: $variable,
                type: $type,
                description: $this->createDescription($description),
            );
        } catch (\Throwable $e) {
            throw InvalidTagException::fromException($e);
        }
    }
}
