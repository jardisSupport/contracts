<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Validation;

/**
 * Immutable value object representing validation results.
 *
 * `$kinds` carries the reason of each failure and is structurally identical to
 * `$errors`: same keys, same nesting, same leaf positions. Each leaf is either
 * {@see self::KIND_MISSING} or {@see self::KIND_INVALID}. An empty `$kinds` tree
 * (callers that predate it) is to be read as "every failure is `invalid`".
 */
final readonly class ValidationResult
{
    public const KIND_MISSING = 'missing';
    public const KIND_INVALID = 'invalid';

    /**
     * @param array<string, mixed> $errors Hierarchical error structure
     * @param array<string, mixed> $kinds Same structure as $errors; leaves are 'missing'|'invalid'
     */
    public function __construct(
        private array $errors = [],
        private array $kinds = []
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getKinds(): array
    {
        return $this->kinds;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getFieldKinds(string $field): array
    {
        return $this->kinds[$field] ?? [];
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * @return array<string, mixed>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getFieldErrors(string $field): array
    {
        return $this->errors[$field] ?? [];
    }

    public function hasFieldError(string $field): bool
    {
        return isset($this->errors[$field]) && !empty($this->errors[$field]);
    }

    /**
     * @return array<string>
     */
    public function getAllFieldsWithErrors(): array
    {
        return array_keys($this->errors);
    }

    public function getErrorCount(): int
    {
        return count($this->errors);
    }

    public function getFirstError(string $field): ?string
    {
        $errors = $this->errors[$field] ?? [];
        return empty($errors) ? null : (string) $errors[0];
    }
}
