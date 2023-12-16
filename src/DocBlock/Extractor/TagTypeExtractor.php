<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Extractor;

use TypeLang\Parser\Node\Name;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\Exception\InvalidTagTypeException;

final class TagTypeExtractor
{
    private static ?NamedTypeNode $mixed = null;

    public function __construct(
        private readonly Parser $parser,
    ) {}

    /**
     * @psalm-immutable
     * @return array{TypeStatement, non-empty-string|null}
     * @throws InvalidTagTypeException
     */
    public function extractTypeOrFail(string $body): array
    {
        try {
            $type = $this->parser->parse($body);

            if ($type instanceof TypeStatement) {
                return [$type, $this->slice($body)];
            }

            throw InvalidTagTypeException::fromNonTyped();
        } catch (\Throwable $e) {
            throw InvalidTagTypeException::fromNonTyped($e);
        }
    }

    /**
     * @return array{TypeStatement, non-empty-string|null}
     */
    public function extractTypeOrMixed(string $body): array
    {
        try {
            return $this->extractTypeOrFail($body);
        } catch (InvalidTagTypeException) {
            return [self::createMixedType(), $body ?: null];
        }
    }

    /**
     * @return array{TypeStatement|null, non-empty-string|null}
     */
    public function extractTypeOrNull(string $body): array
    {
        try {
            return $this->extractTypeOrFail($body);
        } catch (InvalidTagTypeException) {
            return [null, $body ?: null];
        }
    }

    protected function createMixedType(): NamedTypeNode
    {
        return self::$mixed ??= new NamedTypeNode(
            name: new Name('mixed'),
        );
    }

    /**
     * @return non-empty-string|null
     */
    private function slice(string $body): ?string
    {
        $offset = $this->parser->lastProcessedTokenOffset;

        return \trim(\substr($body, $offset)) ?: null;
    }
}
