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
     * @var array<non-empty-lowercase-string, TagFactoryInterface>
     */
    private array $factories;

    /**
     * @param iterable<non-empty-lowercase-string, TagFactoryInterface> $factories
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
        if (\is_string($tags)) {
            $tags = [$tags];
        }

        foreach ($tags as $tag) {
            $this->factories[$tag] = $delegate;
        }
    }

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): TagInterface
    {
        $delegate = $this->factories[\strtolower($tag)] ?? null;

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
                $description = null;

                if ($content !== '') {
                    $description = $descriptions->parse($content);
                }

                return new InvalidTag($tag, $e, $description);
            }
        }

        $description = null;

        if ($content !== '') {
            $description = $descriptions->parse($content);
        }

        return new Tag($tag, $description);
    }
}
