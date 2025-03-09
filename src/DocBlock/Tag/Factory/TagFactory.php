<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Factory;

use TypeLang\PHPDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PHPDoc\DocBlock\Tag\Tag;
use TypeLang\PHPDoc\DocBlock\Tag\TagInterface;
use TypeLang\PHPDoc\Exception\InvalidTagException;
use TypeLang\PHPDoc\Exception\RuntimeExceptionInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

final class TagFactory implements MutableTagFactoryInterface
{
    /**
     * @var array<non-empty-string, TagFactoryInterface>
     */
    private array $factories;

    /**
     * @param iterable<non-empty-string, TagFactoryInterface> $factories
     */
    public function __construct(iterable $factories = [])
    {
        if ($factories instanceof \Traversable) {
            $factories = \iterator_to_array($factories);
        }

        $this->factories = $factories;
    }

    public function register(array|string $tags, TagFactoryInterface $delegate): void
    {
        foreach ((array) $tags as $tag) {
            $this->factories[$tag] = $delegate;
        }
    }

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): TagInterface
    {
        $delegate = $this->factories[$tag] ?? null;

        if ($delegate !== null) {
            try {
                try {
                    return $delegate->create($tag, $content, $descriptions);
                } catch (RuntimeExceptionInterface $e) {
                    throw $e;
                } catch (\Throwable $e) {
                    throw InvalidTagException::fromCreatingTag(
                        tag: $tag,
                        source: $content,
                        prev: $e,
                    );
                }
            } catch (RuntimeExceptionInterface $e) {
                return new InvalidTag(
                    reason: $e,
                    description: $descriptions->parse($content),
                    name: $tag,
                );
            }
        }

        $description = null;

        if ($content !== '') {
            $description = $descriptions->parse($content);
        }

        return new Tag($tag, $description);
    }
}
