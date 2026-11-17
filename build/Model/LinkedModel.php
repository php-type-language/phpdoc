<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

/**
 * The model checked for consistency, with the registrations of every package
 * resolved.
 */
final class LinkedModel
{
    public function __construct(
        public readonly Model $model,
        /**
         * @var array<non-empty-string, LinkedPackage>
         */
        public readonly array $packages,
        /**
         * Tags by their canonical name.
         *
         * @var array<non-empty-string, Tag>
         */
        public readonly array $tags,
        /**
         * Combinators by their name.
         *
         * @var array<non-empty-string, Combinator>
         */
        public readonly array $combinators,
    ) {}

    /**
     * Combinators provided by the parser itself.
     *
     * @return list<Combinator>
     */
    public function getBaseCombinators(): array
    {
        $result = [];

        foreach ($this->combinators as $combinator) {
            if ($combinator->package === null) {
                $result[] = $combinator;
            }
        }

        return $result;
    }

    /**
     * Names under which the tag is registered, by package name.
     *
     * @return array<non-empty-string, list<non-empty-string>>
     */
    public function getRegistrations(Tag $tag): array
    {
        $result = [];

        foreach ($this->packages as $name => $package) {
            foreach ($package->tags as $key => $registered) {
                if ($registered === $tag) {
                    $result[$name][] = $key;
                }
            }

            foreach ($package->aliases as $alias => $target) {
                if (($package->tags[$target] ?? null) === $tag) {
                    $result[$name][] = $alias;
                }
            }
        }

        return $result;
    }
}
