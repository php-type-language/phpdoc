<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Factory;

use TypeLang\PHPDoc\Exception\InvalidTagException;
use TypeLang\PHPDoc\Exception\RuntimeExceptionInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Content;
use TypeLang\PHPDoc\Tag\InvalidTag;
use TypeLang\PHPDoc\Tag\Tag;
use TypeLang\PHPDoc\Tag\TagInterface;

final class TagFactory implements MutableFactoryInterface
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

    public function create(string $name, Content $content, DescriptionParserInterface $descriptions): TagInterface
    {
        $delegate = $this->factories[$name] ?? null;

        if ($delegate !== null) {
            try {
                try {
                    return $delegate->create($name, $content, $descriptions);
                } catch (RuntimeExceptionInterface $e) {
                    throw $e;
                } catch (\Throwable $e) {
                    throw InvalidTagException::fromCreatingTag(
                        tag: $name,
                        source: $content->value,
                        prev: $e,
                    );
                }
            } catch (RuntimeExceptionInterface $e) {
                return new InvalidTag(
                    reason: $e,
                    description: $descriptions->parse($content->source),
                    name: $name,
                );
            }
        }

        $description = null;

        if ($content->value !== '') {
            $description = $content->toDescription($descriptions);
        }

        return new Tag($name, $description);
    }
}
