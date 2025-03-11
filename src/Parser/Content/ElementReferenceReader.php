<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

use TypeLang\Parser\Node\FullQualifiedName;
use TypeLang\Parser\Node\Literal\VariableLiteralNode;
use TypeLang\Parser\Node\Name;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
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
        . '|(?:(?P<T_CLASS>' . self::T_FQN . ')::(?:'
        . '(?:\\$[a-zA-Z_\\x80-\\xff][a-zA-Z0-9_\\x80-\\xff]*+)(*MARK:T_CLASS_PROPERTY)'
        . '|(?:[a-zA-Z_\\x80-\\xff][a-zA-Z0-9_\\x80-\\xff]*+\(\))(*MARK:T_CLASS_METHOD)'
        . '|(?:[a-zA-Z_\\x80-\\xff][a-zA-Z0-9_\\x80-\\xff]*+)(*MARK:T_CLASS_CONSTANT)'
        . '))'
        . '|(?:(?:\\$' . self::T_IDENTIFIER . ')(*MARK:T_VARIABLE))'
        . '|(?:(?:' . self::T_FQN . '\(\))(*MARK:T_FUNCTION))'
        . '|(?:(?:' . self::T_FQN . ')(*MARK:T_IDENTIFIER))'
        . ')(?:\s|$)/Ssum';

    public function __invoke(Stream $stream): ElementReference
    {
        \preg_match(self::SIMPLE_TOKENIZER_PCRE, $stream->value, $matches);

        if ($matches !== []) {
            /** @var non-empty-string $body */
            $body = \rtrim($matches[0]);
            $isFullyQualified = $body[0] === '\\';

            // @phpstan-ignore match.unhandled
            $result = match ($matches['MARK']) {
                // @phpstan-ignore-next-line : All ok
                'T_FUNCTION' => new FunctionElementReference(
                    function: $isFullyQualified
                        // @phpstan-ignore-next-line : All ok
                        ? new FullQualifiedName(\substr($body, 0, -2))
                        // @phpstan-ignore-next-line : All ok
                        : new Name(\substr($body, 0, -2)),
                ),
                // @phpstan-ignore-next-line : All ok
                'T_IDENTIFIER' => new TypeElementReference(
                    type: new NamedTypeNode(
                        name: $isFullyQualified
                            ? new FullQualifiedName($body)
                            : new Name($body)
                    ),
                ),
                'T_VARIABLE' => new VariableReference(
                    variable: new VariableLiteralNode($body),
                ),
                'T_CLASS_CONSTANT' => new ClassConstantElementReference(
                    class: $isFullyQualified
                        // @phpstan-ignore-next-line : All ok
                        ? new FullQualifiedName($matches['T_CLASS'])
                        // @phpstan-ignore-next-line : All ok
                        : new Name($matches['T_CLASS']),
                    // @phpstan-ignore-next-line : All ok
                    constant: \substr($body, \strlen($matches['T_CLASS']) + 2),
                ),
                'T_CLASS_METHOD' => new ClassMethodElementReference(
                    class: $isFullyQualified
                        // @phpstan-ignore-next-line : All ok
                        ? new FullQualifiedName($matches['T_CLASS'])
                        // @phpstan-ignore-next-line : All ok
                        : new Name($matches['T_CLASS']),
                    // @phpstan-ignore-next-line : All ok
                    method: \substr($body, \strlen($matches['T_CLASS']) + 2, -2),
                ),
                'T_CLASS_PROPERTY' => new ClassPropertyElementReference(
                    class: $isFullyQualified
                        // @phpstan-ignore-next-line : All ok
                        ? new FullQualifiedName($matches['T_CLASS'])
                        // @phpstan-ignore-next-line : All ok
                        : new Name($matches['T_CLASS']),
                    // @phpstan-ignore-next-line : All ok
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
}
