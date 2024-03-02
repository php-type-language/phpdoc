<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform;

use TypeLang\Parser\Node\SerializableInterface;
use TypeLang\PHPDoc\Tag\Platform\Version\Version;
use TypeLang\PHPDoc\Tag\Platform\Version\VersionInterface;

enum Platform implements PlatformInterface
{
    #[Info(name: 'phpDocumentor', description: <<<'DESCRIPTION'
        phpDocumentor is the de-facto documentation application for PHP
        projects. Your project can benefit too from more than 20 years of
        experience and setting the standard for documenting PHP Applications.
        DESCRIPTION)]
    case PHPDOCUMENTOR;

    #[Info(name: 'PhpStorm', description: <<<'DESCRIPTION'
        The Lightning-Smart PHP IDE.
        DESCRIPTION)]
    case PHPSTORM;

    #[Info(name: 'PHPStan', description: <<<'DESCRIPTION'
        PHPStan finds bugs in your code without writing tests.
        It's open-source and free.
        DESCRIPTION)]
    case PHPSTAN;

    #[Info(name: 'Psalm', description: <<<'DESCRIPTION'
        Psalm is a free & open-source static analysis tool that helps you
        identify problems in your code, so you can sleep a little better.
        DESCRIPTION)]
    case PSALM;

    #[Info(name: 'Phan', description: <<<'DESCRIPTION'
        Phan is a static analyzer for PHP that prefers to minimize
        false-positives. Phan attempts to prove incorrectness rather than
        correctness.
        DESCRIPTION)]
    case PHAN;

    #[Info(name: 'PHPUnit', description: <<<'DESCRIPTION'
        PHPUnit is a programmer-oriented testing framework for PHP. It is an
        instance of the xUnit architecture for unit testing frameworks.
        DESCRIPTION)]
    case PHPUNIT;

    /**
     * @var list<Platform>
     */
    public const ANY_STATIC_ANALYSIS = [
        self::PHAN,
        self::PHPSTAN,
        self::PSALM,
        self::PHPSTORM,
    ];

    /**
     * @var list<Platform>
     */
    public const ANY_LANGUAGE_AWARE = [
        ...self::ANY_STATIC_ANALYSIS,
        self::PHPDOCUMENTOR,
    ];

    public function getName(): string
    {
        $info = $this->getInfo();

        return $info->name ?? $this->name;
    }

    public function getDescription(): \Stringable|string|null
    {
        $info = $this->getInfo();

        return $info->description;
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     description: array|string|null
     * }
     */
    public function toArray(): array
    {
        $description = $this->getDescription();

        return [
            'name' => $this->name,
            'description' => match (true) {
                $description === null => null,
                $description instanceof SerializableInterface => $description->toArray(),
                default => (string) $description,
            },
        ];
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     description: array|string|null
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    private function getInfo(): Info
    {
        /**
         * Local identity map for Info metadata objects.
         *
         * @var array<non-empty-string, Info> $memory
         */
        static $memory = [];

        if (isset($memory[$this->name])) {
            return $memory[$this->name];
        }

        $attributes = (new \ReflectionEnumUnitCase(self::class, $this->name))
            ->getAttributes(Info::class);

        if (isset($attributes[0])) {
            return $memory[$this->name] = $attributes[0]->newInstance();
        }

        return new Info();
    }

    /**
     * @param VersionInterface|\Stringable|non-empty-string $version
     */
    public function withVersion(
        VersionInterface|\Stringable|string $version = new Version(),
        VersionInterface|\Stringable|string $until = null,
    ): PlatformVersionInterface {
        return new PlatformVersion($this, $version, $until);
    }
}
