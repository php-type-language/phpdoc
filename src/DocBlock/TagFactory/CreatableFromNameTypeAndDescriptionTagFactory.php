<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\TagFactory;

use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Reader\OptionalTypeReader;
use TypeLang\PhpDocParser\DocBlock\Reader\TolerantTypeReader;
use TypeLang\PhpDocParser\DocBlock\Reader\VariableNameReader;
use TypeLang\PhpDocParser\DocBlock\Tag\CreatableFromNameTypeAndDescriptionInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\TypedTag;
use TypeLang\PhpDocParser\Exception\InvalidTagException;

/**
 * @template TTag of CreatableFromNameTypeAndDescriptionInterface
 * @template-extends TagFactory<TTag>
 */
final class CreatableFromNameTypeAndDescriptionTagFactory extends TagFactory
{
    private readonly VariableNameReader $variables;

    private readonly TolerantTypeReader $types;

    /**
     * @param class-string<TTag> $class
     */
    public function __construct(
        private readonly string $class,
        Parser $parser = new Parser(true),
        ?DescriptionFactoryInterface $descriptions = null,
    ) {
        $this->variables = new VariableNameReader();
        $this->types = new TolerantTypeReader(new OptionalTypeReader($parser));

        parent::__construct($descriptions);
    }

    public function create(string $content): TypedTag
    {
        $type = $this->types->read($content);
        $content = \substr($content, $type->offset);

        $selection = $this->variables->read($content);
        $content = \substr($content, $selection->offset);

        try {
            return $this->class::createFromNameTypeAndDescription(
                name: $selection->data,
                type: $type->data,
                description: $this->createDescription($content),
            );
        } catch (\Throwable $e) {
            throw InvalidTagException::fromException($e);
        }
    }
}
