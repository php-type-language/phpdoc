<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

use TypeLang\Parser\Node\FullQualifiedName;
use TypeLang\Parser\Node\Literal\VariableLiteralNode;
use TypeLang\Parser\Node\Name;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\ClassConstantElementReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\ClassMethodElementReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\ClassPropertyElementReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\ElementReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\FunctionElementReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\TypeElementReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\VariableReference;

/**
 * @template-implements ReaderInterface<ElementReference>
 */
final class ElementReferenceReader implements ReaderInterface
{
    private const T_FQN = '(?:[\\\\]?+[a-zA-Z\x80-\xFF_][0-9a-zA-Z\x80-\xFF_-]*+)++';
    private const T_IDENTIFIER = '[a-zA-Z_\\x80-\\xff][a-zA-Z0-9\\-_\\x80-\\xff]*+';

    private const SIMPLE_TOKENIZER_PCRE = '/^(?'
        . '|(?:(?P<T_CLASS>'. self::T_FQN . ')::(?:'
            . '(?:\\$[a-zA-Z_\\x80-\\xff][a-zA-Z0-9_\\x80-\\xff]*+)(*MARK:T_CLASS_PROPERTY)'
            . '|(?:[a-zA-Z_\\x80-\\xff][a-zA-Z0-9_\\x80-\\xff]*+\(\))(*MARK:T_CLASS_METHOD)'
            . '|(?:[a-zA-Z_\\x80-\\xff][a-zA-Z0-9_\\x80-\\xff]*+)(*MARK:T_CLASS_CONSTANT)'
        . '))'
        . '|(?:(?:\\$'. self::T_IDENTIFIER . ')(*MARK:T_VARIABLE))'
        . '|(?:(?:'. self::T_FQN . '\(\))(*MARK:T_FUNCTION))'
        . '|(?:(?:'. self::T_FQN . ')(*MARK:T_IDENTIFIER))'
    . ')(?:\s|$)/Ssum';

    public function __invoke(Stream $stream): ElementReference
    {
        \preg_match(self::SIMPLE_TOKENIZER_PCRE, $stream->value, $matches);

        if ($matches !== []) {
            /** @var non-empty-string $body */
            $body = \rtrim($matches[0]);
            $isFullyQualified = $body[0] === '\\';

            $result = match ($matches['MARK']) {
                'T_FUNCTION' => new FunctionElementReference($isFullyQualified
                    ? new FullQualifiedName(\substr($body, 0, -2))
                    : new Name(\substr($body, 0, -2)),
                ),
                'T_IDENTIFIER' => new TypeElementReference(
                    type: new NamedTypeNode($isFullyQualified
                        ? new FullQualifiedName($body)
                        : new Name($body)
                    ),
                ),
                'T_VARIABLE' => new VariableReference(
                    variable: new VariableLiteralNode($body),
                ),
                'T_CLASS_CONSTANT' => new ClassConstantElementReference(
                    class: $isFullyQualified
                        ? new FullQualifiedName($matches['T_CLASS'])
                        : new Name($matches['T_CLASS']),
                    constant: \substr($body, \strlen($matches['T_CLASS']) + 2),
                ),
                'T_CLASS_METHOD' => new ClassMethodElementReference(
                    class: $isFullyQualified
                        ? new FullQualifiedName($matches['T_CLASS'])
                        : new Name($matches['T_CLASS']),
                    method: \substr($body, \strlen($matches['T_CLASS']) + 2, -2),
                ),
                'T_CLASS_PROPERTY' => new ClassPropertyElementReference(
                    class: $isFullyQualified
                        ? new FullQualifiedName($matches['T_CLASS'])
                        : new Name($matches['T_CLASS']),
                    property: \substr($body, \strlen($matches['T_CLASS']) + 3),
                ),
            };

            $stream->shift(\strlen($matches[0]));

            return $result;
        }

        throw $stream->toException(\sprintf(
            'Tag @%s expects the FQN reference to be defined',
            $stream->tag,
        ));
    }

    private function getReference(string $context, Stream $stream): ElementReference
    {
        $class = new Name($context);

        if (\str_starts_with($stream->value, '::')) {
            $stream->shift(2, false);

            if (\str_starts_with($stream->value, '$')) {
                $stream->shift(1, false);

                $variable = $this->fetchIdentifier($stream->value);

                if ($variable !== null) {
                    return new ClassPropertyElementReference(
                        class: $class,
                        property: $variable,
                    );
                }

                throw $stream->toException(\sprintf(
                    'Tag @%s contains invalid property name after class reference',
                    $stream->tag,
                ));
            }

            $identifier = $this->fetchIdentifier($stream->value);

            if ($identifier !== null) {
                $stream->shift(\strlen($identifier), false);

                if (\str_starts_with($stream->value, '()')) {
                    return new ClassMethodElementReference(
                        class: $class,
                        method: $identifier,
                    );
                }

                return new ClassConstantElementReference(
                    class: $class,
                    constant: $identifier,
                );
            }

            throw $stream->toException(\sprintf(
                'Tag @%s contains invalid method or constant name after class reference',
                $stream->tag,
            ));
        }

        if (\str_starts_with($stream->value, '()')) {
            $stream->shift(2, false);

            return new FunctionElementReference($class);
        }

        return new TypeElementReference(
            type: new NamedTypeNode($class),
        );
    }
}
