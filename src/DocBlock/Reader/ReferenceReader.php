<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reader;

use TypeLang\Parser\Node\FullQualifiedName;
use TypeLang\Parser\Node\Identifier;
use TypeLang\Parser\Node\Literal\VariableLiteralNode;
use TypeLang\Parser\Node\Name;
use TypeLang\PhpDocParser\DocBlock\Reference\ClassConstReference;
use TypeLang\PhpDocParser\DocBlock\Reference\ClassPropertyReference;
use TypeLang\PhpDocParser\DocBlock\Reference\FunctionReference;
use TypeLang\PhpDocParser\DocBlock\Reference\GenericReference;
use TypeLang\PhpDocParser\DocBlock\Reference\MethodReference;
use TypeLang\PhpDocParser\DocBlock\Reference\NameReference;
use TypeLang\PhpDocParser\DocBlock\Reference\ReferenceInterface;
use TypeLang\PhpDocParser\DocBlock\Reference\UriReference;

/**
 * @template-extends Reader<ReferenceInterface>
 */
final class ReferenceReader extends Reader
{
    /**
     * @return Sequence<ReferenceInterface>
     */
    public function read(string $content): Sequence
    {
        $description = \strpbrk($content, " \t\n\r\0\x0B");

        if ($description === false) {
            return new Sequence(
                data: $this->parse($content),
                offset: \strlen($content),
            );
        }

        $descriptionOffset = \strlen($content) - \strlen($description);

        return new Sequence(
            data: $this->parse(\substr($content, 0, $descriptionOffset)),
            offset: $descriptionOffset,
        );
    }

    private function parse(string $body): ReferenceInterface
    {
        if (($result = $this->tryParseUriReference($body)) !== null) {
            return $result;
        }

        if (\str_ends_with($body, '()')) {
            return $this->tryParseCallableReference($body)
                ?? new GenericReference($body);
        }

        if (\str_contains($body, '::')) {
            $result = \str_contains($body, '$')
                ? $this->tryParseClassProperty($body)
                : $this->tryParseClassConst($body);

            return $result ?? new GenericReference($body);
        }

        if (($result = $this->tryParseNameReference($body)) !== null) {
            return $result;
        }

        return new GenericReference($body);
    }

    private function tryParseClassProperty(string $body): ?ClassPropertyReference
    {
        $parts = \explode('::', $body);

        if (\count($parts) !== 2) {
            return null;
        }

        if (($variable = $this->tryParseVariable($parts[1])) === null) {
            return null;
        }

        if (($name = $this->tryParseName($parts[0])) === null) {
            return null;
        }

        return new ClassPropertyReference($name, $variable);
    }

    private function tryParseClassConst(string $body): ?ClassConstReference
    {
        $parts = \explode('::', $body);

        if (\count($parts) !== 2) {
            return null;
        }

        if (($callable = $this->tryParseIdentifier($parts[1])) === null) {
            return null;
        }

        if (($name = $this->tryParseName($parts[0])) === null) {
            return null;
        }

        return new ClassConstReference($name, $callable);
    }

    private function tryParseNameReference(string $body): ?NameReference
    {
        if (\ctype_alpha(\str_replace('\\', '', $body))) {
            if (\str_starts_with($body, '\\')) {
                return new NameReference(new FullQualifiedName($body));
            }

            return new NameReference(new Name($body));
        }

        return null;
    }

    private function tryParseCallableReference(string $body): FunctionReference|MethodReference|null
    {
        if (\str_contains($body, '::')) {
            return $this->tryParseMethod($body);
        }

        return $this->tryParseFunction($body);
    }

    private function tryParseFunction(string $body): ?FunctionReference
    {
        $callable = $this->tryParseName(\substr($body, 0, -2));

        if ($callable !== null) {
            return new FunctionReference($callable);
        }

        return null;
    }

    private function tryParseMethod(string $body): ?MethodReference
    {
        $parts = \explode('::', \substr($body, 0, -2));

        if (\count($parts) !== 2) {
            return null;
        }

        if (($callable = $this->tryParseIdentifier($parts[1])) === null) {
            return null;
        }

        if (($name = $this->tryParseName($parts[0])) === null) {
            return null;
        }

        return new MethodReference($name, $callable);
    }

    private function tryParseName(string $name): ?Name
    {
        if ($fqn = \str_starts_with($name, '\\')) {
            $name = \substr($name, 1);
        }

        $parts = [];

        foreach (\explode('\\', $name) as $chunk) {
            $identifier = $this->tryParseIdentifier($chunk);

            if ($identifier === null) {
                return null;
            }

            $parts[] = $identifier;
        }

        return $fqn ? new FullQualifiedName($parts) : new Name($parts);
    }

    private function tryParseVariable(string $name): ?VariableLiteralNode
    {
        if (\preg_match('/^\$[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $name) === 0) {
            return null;
        }

        return new VariableLiteralNode($name);
    }

    private function tryParseIdentifier(string $name): ?Identifier
    {
        if (\preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $name) === 0) {
            return null;
        }

        return new Identifier($name);
    }

    private function tryParseUriReference(string $body): ?UriReference
    {
        $result = \parse_url($body);

        if (!\is_array($result) || !isset($result['host'])) {
            return null;
        }

        return new UriReference(
            scheme: $result['scheme'] ?? '',
            host: $result['host'],
            port: $result['port'] ?? null,
            path: $result['path'] ?? '/',
            query: $result['query'] ?? '',
            fragment: $result['fragment'] ?? '',
            user: $result['user'] ?? '',
            password: $result['pass'] ?? '',
        );
    }
}
