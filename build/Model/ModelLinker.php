<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;

/**
 * Checks the model for consistency and resolves what each package registers.
 *
 * Every problem is collected, the model is rejected with all of them at once.
 */
final class ModelLinker
{
    /**
     * @var list<non-empty-string>
     */
    private array $errors = [];

    /**
     * @throws ModelException in case of the model is inconsistent
     */
    public function link(Model $model): LinkedModel
    {
        $this->errors = [];

        $packages = $this->indexPackages($model);
        $combinators = $this->indexCombinators($model, $packages);
        $tags = $this->indexTags($model, $packages);
        $aliases = $this->indexAliases($model, $packages);

        $linked = [];

        foreach ($packages as $name => $package) {
            $linked[$name] = $this->linkPackage($package, $tags, $aliases, $combinators);
        }

        $this->checkSharedNames($linked);

        $result = new LinkedModel($model, $linked, $tags, $combinators);

        foreach ($tags as $tag) {
            $this->checkTag($tag, $result);
        }

        if ($this->errors !== []) {
            throw ModelException::becauseModelIsInvalid($this->errors);
        }

        return $result;
    }

    /**
     * @return array<non-empty-string, Package>
     */
    private function indexPackages(Model $model): array
    {
        $result = [];
        $classes = [];

        foreach ($model->packages as $package) {
            if (isset($result[$package->name])) {
                $this->addError(
                    $package->file,
                    'package "%s" is declared twice',
                    \addcslashes($package->name, '"'),
                );
            }

            if (isset($classes[$package->class])) {
                $this->addError($package->file, 'platform class %s is used twice', $package->class);
            }

            $result[$package->name] = $package;
            $classes[$package->class] = true;
        }

        return $result;
    }

    /**
     * @param array<non-empty-string, Package> $packages
     * @return array<non-empty-string, Combinator>
     */
    private function indexCombinators(Model $model, array $packages): array
    {
        $result = [];

        foreach ($model->combinators as $combinator) {
            if (isset($result[$combinator->name])) {
                $this->addError($combinator->file, 'combinator <%s> is declared twice', $combinator->name);
            }

            if ($combinator->package !== null && !isset($packages[$combinator->package])) {
                $this->addError(
                    $combinator->file,
                    'combinator <%s>: unknown package "%s"',
                    $combinator->name,
                    \addcslashes($combinator->package, '"'),
                );
            }

            if (!\class_exists($combinator->class)) {
                $this->addError(
                    $combinator->file,
                    'combinator <%s>: class %s not found',
                    $combinator->name,
                    $combinator->class,
                );
            } elseif ($combinator->isInstanceGenerated) {
                $this->checkCombinatorConstructor($combinator);
            } elseif ($combinator->package !== null) {
                $this->addError(
                    $combinator->file,
                    'combinator <%s>: only a combinator of the parser is created manually',
                    $combinator->name,
                );
            }

            $result[$combinator->name] = $combinator;
        }

        \ksort($result);

        return $result;
    }

    /**
     * The generated code passes nothing to the constructor, or the type
     * parser for a combinator of the parser.
     */
    private function checkCombinatorConstructor(Combinator $combinator): void
    {
        \assert(\class_exists($combinator->class));

        $constructor = (new \ReflectionClass($combinator->class))->getConstructor();

        foreach ($constructor?->getParameters() ?? [] as $parameter) {
            if ($parameter->isOptional()) {
                continue;
            }

            if ($combinator->package === null && CombinatorArguments::isTypeParser($parameter)) {
                continue;
            }

            $this->addError(
                $combinator->file,
                'combinator <%s>: the generated code cannot pass $%s to the constructor',
                $combinator->name,
                $parameter->name,
            );
        }
    }

    /**
     * @param array<non-empty-string, Package> $packages
     * @return array<non-empty-string, Tag>
     */
    private function indexTags(Model $model, array $packages): array
    {
        $result = [];
        $names = [];
        $classes = [];

        foreach ($model->tags as $tag) {
            $lower = \strtolower($tag->name);

            if (isset($names[$lower])) {
                $this->addError($tag->file, 'tag @%s is declared twice', $tag->name);
            }

            if (isset($classes[$tag->class])) {
                $this->addError($tag->file, 'tag @%s: class %s is used twice', $tag->name, $tag->class);
            }

            if ($tag->package !== null && !isset($packages[$tag->package])) {
                $this->addError(
                    $tag->file,
                    'tag @%s: unknown package "%s"',
                    $tag->name,
                    \addcslashes($tag->package, '"'),
                );
            }

            $names[$lower] = true;
            $classes[$tag->class] = true;
            $result[$tag->name] = $tag;
        }

        return $result;
    }

    /**
     * @param array<non-empty-string, Package> $packages
     * @return array<non-empty-string, array<non-empty-lowercase-string, Alias>>
     */
    private function indexAliases(Model $model, array $packages): array
    {
        $result = [];

        foreach ($model->aliases as $alias) {
            $lower = \strtolower($alias->name);

            if (!isset($packages[$alias->package])) {
                $this->addError(
                    $alias->file,
                    'alias @%s: unknown package "%s"',
                    $alias->name,
                    \addcslashes($alias->package, '"'),
                );
            }

            if (isset($result[$alias->package][$lower])) {
                $this->addError(
                    $alias->file,
                    'alias @%s is declared twice in "%s"',
                    $alias->name,
                    \addcslashes($alias->package, '"'),
                );
            }

            $result[$alias->package][$lower] = $alias;
        }

        return $result;
    }

    /**
     * @param array<non-empty-string, Tag> $tags
     * @param array<
     *     non-empty-string,
     *     array<non-empty-lowercase-string, Alias>,
     * > $aliases
     * @param array<non-empty-string, Combinator> $combinators
     */
    private function linkPackage(
        Package $package,
        array $tags,
        array $aliases,
        array $combinators,
    ): LinkedPackage {
        $registered = [];
        $runtime = [];

        foreach ($tags as $tag) {
            if ($tag->package === $package->name) {
                $registered[$tag->name] = $tag;
            }
        }

        foreach ($aliases[$package->name] ?? [] as $alias) {
            $target = $this->resolveAlias($alias, $tags, $aliases);

            if ($target === null) {
                continue;
            }

            if ($this->isRegisteredIn($alias->target, $package->name, $tags, $aliases)) {
                $runtime[$alias->name] = $alias->target;

                continue;
            }

            $registered[$alias->name] = $target;
        }

        $keys = [];

        foreach ([...\array_keys($registered), ...\array_keys($runtime)] as $key) {
            $lower = \strtolower((string) $key);

            if (isset($keys[$lower])) {
                $this->addError(
                    $package->file,
                    'name @%s is registered twice in "%s"',
                    (string) $key,
                    \addcslashes($package->name, '"'),
                );
            }

            $keys[$lower] = true;
        }

        $own = [];

        foreach ($combinators as $combinator) {
            if ($combinator->package === $package->name) {
                $own[] = $combinator;
            }
        }

        \ksort($registered, \SORT_STRING);
        \ksort($runtime, \SORT_STRING);

        return new LinkedPackage($package, $registered, $runtime, $own);
    }

    /**
     * No name is registered by two packages, the built-in platforms are
     * loaded in any order with the same result.
     *
     * @param array<non-empty-string, LinkedPackage> $packages
     */
    private function checkSharedNames(array $packages): void
    {
        $owners = [];

        foreach ($packages as $name => $linked) {
            foreach ([...\array_keys($linked->tags), ...\array_keys($linked->aliases)] as $key) {
                $owners['@' . \strtolower((string) $key)][] = $name;
            }

            foreach ($linked->combinators as $combinator) {
                $owners['<' . $combinator->name . '>'][] = $name;
            }
        }

        foreach ($owners as $key => $names) {
            if (\count($names) > 1) {
                $escaped = \array_map(
                    static fn(string $name): string => \addcslashes($name, '"'),
                    $names,
                );

                $this->addError(
                    $packages[$names[0]]->package->file,
                    '%s is registered by "%s"',
                    $key,
                    \implode('", "', $escaped),
                );
            }
        }
    }

    /**
     * Follows the alias chain down to the tag.
     *
     * @param array<non-empty-string, Tag> $tags
     * @param array<
     *     non-empty-string,
     *     array<non-empty-lowercase-string, Alias>,
     * > $aliases
     */
    private function resolveAlias(Alias $alias, array $tags, array $aliases): ?Tag
    {
        $visited = [];
        $current = $alias;

        while (true) {
            $visited[] = $current->name;
            $next = $aliases[$alias->package][\strtolower($current->target)] ?? null;

            if ($next !== null) {
                if (\in_array($next->name, $visited, true)) {
                    $this->addError(
                        $alias->file,
                        'alias @%s is recursive: %s',
                        $alias->name,
                        \implode(' -> ', $visited),
                    );

                    return null;
                }

                $current = $next;

                continue;
            }

            $tag = $this->findTag($current->target, $tags);

            if ($tag === null) {
                $this->addError(
                    $alias->file,
                    'alias @%s: @%s is neither a tag nor an alias',
                    $alias->name,
                    $current->target,
                );
            }

            return $tag;
        }
    }

    /**
     * Whether the name is a tag of the package or an alias of it.
     *
     * @param array<non-empty-string, Tag> $tags
     * @param array<
     *     non-empty-string,
     *     array<non-empty-lowercase-string, Alias>,
     * > $aliases
     */
    private function isRegisteredIn(string $name, string $package, array $tags, array $aliases): bool
    {
        if (isset($aliases[$package][\strtolower($name)])) {
            return true;
        }

        return $this->findTag($name, $tags)?->package === $package;
    }

    /**
     * @param array<non-empty-string, Tag> $tags
     */
    private function findTag(string $name, array $tags): ?Tag
    {
        foreach ($tags as $tag) {
            if (\strtolower($tag->name) === \strtolower($name)) {
                return $tag;
            }
        }

        return null;
    }

    private function checkTag(Tag $tag, LinkedModel $model): void
    {
        $registrations = $model->getRegistrations($tag);

        if ($registrations === []) {
            $this->addError($tag->file, 'tag @%s is registered by no package', $tag->name);
        }

        $captures = Captures::collect($tag->grammar);

        foreach ($captures->combinators as $name) {
            $combinator = $model->combinators[$name] ?? null;

            if ($combinator === null) {
                $this->addError($tag->file, 'tag @%s: unknown combinator <%s>', $tag->name, $name);

                continue;
            }

            foreach (\array_keys($registrations) as $package) {
                if ($combinator->package !== null && $combinator->package !== $package) {
                    $this->addError(
                        $tag->file,
                        'tag @%s: combinator <%s> of "%s" is not available in "%s"',
                        $tag->name,
                        $name,
                        \addcslashes($combinator->package, '"'),
                        \addcslashes($package, '"'),
                    );
                }
            }
        }

        $this->checkClasses($tag);

        if ($tag->isDefinitionGenerated) {
            $this->checkParameters($tag, $captures);
        }

        if ($tag->since !== null && $tag->deprecated !== null && \version_compare($tag->deprecated, $tag->since, '<')) {
            $this->addError(
                $tag->file,
                'tag @%s: deprecated %s before since %s',
                $tag->name,
                $tag->deprecated,
                $tag->since,
            );
        }
    }

    private function checkClasses(Tag $tag): void
    {
        if ($tag->extends === null && !\class_exists($tag->class)) {
            $this->addError($tag->file, 'tag @%s: class %s is written by hand but not found', $tag->name, $tag->class);
        }

        if ($tag->extends !== null && !\class_exists($tag->extends)) {
            $this->addError($tag->file, 'tag @%s: base class %s not found', $tag->name, $tag->extends);
        }

        foreach ($tag->templateArguments as $argument) {
            if (!\class_exists($argument) && !\interface_exists($argument)) {
                $this->addError($tag->file, 'tag @%s: template argument %s not found', $tag->name, $argument);
            }
        }

        if ($tag->isDefinitionGenerated) {
            return;
        }

        $definition = $tag->getDefinitionClass();

        if (!\class_exists($definition) || !\is_subclass_of($definition, TagDefinitionInterface::class)) {
            $this->addError($tag->file, 'tag @%s: manual definition %s not found', $tag->name, $definition);
        }
    }

    /**
     * The constructor of the tag class receives every capture, and every
     * parameter without a default value is captured on each match.
     */
    private function checkParameters(Tag $tag, Captures $captures): void
    {
        $constructor = $this->findConstructor($tag);

        if ($constructor === null) {
            return;
        }

        $parameters = [];

        foreach ($constructor->getParameters() as $parameter) {
            $parameters[$parameter->name] = $parameter;
        }

        if (!isset($parameters['name'])) {
            $this->addError($tag->file, 'tag @%s: the tag class has no $name parameter', $tag->name);
        }

        $bound = ['name' => true];

        foreach ($captures->names as $capture) {
            $name = $tag->getParameterName($capture);
            $parameter = $parameters[$name] ?? null;
            $bound[$name] = true;

            if ($parameter === null) {
                $this->addError(
                    $tag->file,
                    'tag @%s: capture "%s" has no parameter $%s',
                    $tag->name,
                    \addcslashes($capture, '"'),
                    $name,
                );

                continue;
            }

            if ($captures->isLiteral($capture)) {
                if ((string) $parameter->getType() !== 'bool') {
                    $this->addError(
                        $tag->file,
                        'tag @%s: literal capture "%s" goes to non-bool $%s',
                        $tag->name,
                        \addcslashes($capture, '"'),
                        $name,
                    );
                }

                continue;
            }

            if (!$captures->isRequired($capture) && !$captures->isRepeated($capture) && !$parameter->allowsNull()) {
                $this->addError(
                    $tag->file,
                    'tag @%s: optional capture "%s" goes to non-nullable $%s',
                    $tag->name,
                    \addcslashes($capture, '"'),
                    $name,
                );
            }
        }

        foreach ($parameters as $name => $parameter) {
            if (!isset($bound[$name]) && !$parameter->isOptional()) {
                $this->addError(
                    $tag->file,
                    'tag @%s: required parameter $%s is captured by nothing',
                    $tag->name,
                    $name,
                );
            }
        }
    }

    private function findConstructor(Tag $tag): ?\ReflectionMethod
    {
        $class = $tag->extends ?? $tag->class;

        if (!\class_exists($class)) {
            return null;
        }

        return (new \ReflectionClass($class))->getConstructor();
    }

    private function addError(string $file, string $format, string|int ...$arguments): void
    {
        $message = \sprintf('%s: ' . $format, $file, ...$arguments);

        \assert($message !== '');

        $this->errors[] = $message;
    }
}
