<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Extractor;

use TypeLang\Parser\Node\Name;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\ParserInterface;
use TypeLang\Parser\Traverser;
use TypeLang\PhpDocParser\Exception\InvalidTagTypeException;
use TypeLang\PhpDocParser\Visitor\NodeCompleteOffsetVisitor;

final class TagTypeExtractor
{
    private static ?NamedTypeNode $mixed = null;

    public function __construct(
        private readonly ParserInterface $parser,
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
                return [$type, $this->slice($type, $body)];
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

    protected function createMixedType(): NamedTypeNode
    {
        return self::$mixed ??= new NamedTypeNode(
            name: new Name('mixed'),
        );
    }

    /**
     * @return non-empty-string|null
     */
    private function slice(TypeStatement $type, string $body): ?string
    {
        $offset = $this->getTypeOffset($type);

        return \ltrim(\substr($body, $offset)) ?: null;
    }

    /**
     * @return int<0, max>
     */
    public function getTypeOffset(TypeStatement $stmt): int
    {
        $visitor = Traverser::through(new NodeCompleteOffsetVisitor(), [$stmt]);

        return $visitor->offset;
    }
}
