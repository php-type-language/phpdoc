<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

use TypeLang\Parser\Node\Literal\VariableLiteralNode;
use TypeLang\Parser\Node\Stmt\CallableTypeNode;
use TypeLang\Parser\Node\Stmt\ClassConstNode;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\ClassConstantElementReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\ClassMethodElementReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\ClassPropertyElementReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\ElementReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\FunctionReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\TypeElementReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\VariableReference;

/**
 * @template-implements ReaderInterface<ElementReference>
 */
final class ElementReferenceReader implements ReaderInterface
{
    private OptionalTypeReader $types;

    public function __construct(TypesParserInterface $parser)
    {
        $this->types = new OptionalTypeReader($parser);
    }

    public function __invoke(Stream $stream): ElementReference
    {
        $type = ($this->types)($stream);

        if ($type instanceof CallableTypeNode) {
            return $this->createFromFunction($type);
        }

        if ($type instanceof NamedTypeNode) {
            return $this->createFromNamedType($type, $stream);
        }

        if (\str_starts_with($stream->value, '$')) {
            return new VariableReference(VariableLiteralNode::parse(
                value: $stream->apply(new VariableNameReader()),
            ));
        }

        if ($type instanceof ClassConstNode) {
            /**
             * @var non-empty-string $identifier
             *
             * @phpstan-ignore-next-line constant cannot be null
             */
            $identifier = $type->constant->toString();

            if (\str_starts_with($stream->value, '()')) {
                return new ClassMethodElementReference($type->class, $identifier);
            }

            return new ClassConstantElementReference($type->class, $identifier);
        }

        throw $stream->toException(\sprintf(
            'Tag @%s expects the FQN reference to be defined',
            $stream->tag,
        ));
    }

    private function createFromFunction(CallableTypeNode $type): ElementReference
    {
        if ($type->type !== null || $type->parameters->items !== []) {
            return new TypeElementReference($type);
        }

        return new FunctionReference($type->name);
    }

    private function createFromNamedType(NamedTypeNode $type, Stream $stream): ElementReference
    {
        if (\str_starts_with($stream->value, '::')) {
            if ($type->arguments === null && $type->fields === null) {
                // Skip "::" delimiter
                $stream->shift(2);

                $variable = $stream->apply(new OptionalVariableNameReader());

                if ($variable !== null) {
                    return new ClassPropertyElementReference(
                        class: $type->name,
                        property: $variable,
                    );
                }
            }

            throw $stream->toException(\sprintf(
                'Tag @%s expects the FQN reference to be defined',
                $stream->tag,
            ));
        }

        return new TypeElementReference($type);
    }
}
