<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

use TypeLang\PHPDoc\Exception\InvalidTagException;
use TypeLang\PHPDoc\Exception\ParsingException;
use TypeLang\PHPDoc\Exception\RuntimeExceptionInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Tag;

final class Factory implements MutableFactoryInterface
{
    /**
     * @param array<non-empty-string, FactoryInterface> $factories
     */
    public function __construct(
        private array $factories = [],
    ) {}

    public function register(array|string $tags, FactoryInterface $delegate): void
    {
        foreach ((array) $tags as $tag) {
            $this->factories[$tag] = $delegate;
        }
    }

    public function create(string $name, string $content, DescriptionParserInterface $descriptions): Tag
    {
        $delegate = $this->factories[$name] ?? null;

        if ($delegate !== null) {
            try {
                return $delegate->create($name, $content, $descriptions);
            } catch (ParsingException $e) {
                throw $e;
            } catch (RuntimeExceptionInterface $e) {
                throw InvalidTagException::fromCreatingTag(
                    tag: $name,
                    source: $e->getSource(),
                    offset: $e->getOffset(),
                    prev: $e,
                );
            } catch (\Throwable $e) {
                throw InvalidTagException::fromCreatingTag(
                    tag: $name,
                    source: $content,
                    prev: $e,
                );
            }
        }

        return new Tag($name, $descriptions->parse($content));
    }
}
