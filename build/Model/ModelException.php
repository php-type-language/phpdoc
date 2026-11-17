<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

/**
 * The model files are malformed or inconsistent.
 */
final class ModelException extends \RuntimeException
{
    public function __construct(
        /**
         * Every problem found, one message each.
         *
         * @var non-empty-list<non-empty-string>
         */
        public readonly array $errors,
    ) {
        parent::__construct(\sprintf(
            "The model is invalid:\n  - %s",
            \implode("\n  - ", $errors),
        ));
    }

    /**
     * @param non-empty-list<non-empty-string> $errors
     */
    public static function becauseModelIsInvalid(array $errors): self
    {
        return new self($errors);
    }

    /**
     * @param non-empty-string $error
     */
    public static function becauseFileIsInvalid(string $file, string $error): self
    {
        return new self([\sprintf('%s: %s', $file, $error)]);
    }
}
