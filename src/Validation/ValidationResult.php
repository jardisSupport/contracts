<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Validation;

/**
 * Immutable value object representing validation results.
 */
final readonly class ValidationResult
{
    /**
     * @param array<string, mixed> $errors Hierarchical error structure
     */
    public function __construct(
        private array $errors = []
    ) {
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
