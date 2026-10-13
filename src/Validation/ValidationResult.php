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

    /** Reason of a finding whose validator kind is {@see self::KIND_MISSING}. */
    public const REASON_MISSING = 'missing';
    /** Reason of every other finding (including one without a kind). */
    public const REASON_INVALID = 'invalid';

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

    /**
     * Flattens the error tree into a list of findings, one per message.
     *
     * - `field` is the dot path of the string keys down to the message; list
     *   indices (int keys) add no segment of their own.
     * - `reason` is {@see self::REASON_MISSING} exactly when the structurally equal
     *   `$kinds` leaf is {@see self::KIND_MISSING}; every other finding, also one
     *   without a kind, is {@see self::REASON_INVALID}. There is no text fallback.
     * - `message` is the validator text as delivered (may be `''`).
     * - `$root`: a TOP-LEVEL segment equal to `$root` is stripped from the path
     *   (the aggregate root carries no prefix); every other top-level segment stays
     *   in it. Without `$root` nothing is stripped. Nested segments are never stripped.
     *
     * The answer vocabulary (default texts, message keys) is not part of this
     * method; the caller maps `reason` to it.
     *
     * Example:
     * ```php
     * // errors: ['order' => ['email' => ['Required'], 'line' => ['qty' => ['Too small'], 0 => 'x']]]
     * // kinds:  ['order' => ['email' => ['missing'],  'line' => ['qty' => ['invalid'], 0 => 'invalid']]]
     * $result->toFields();
     * // [['field' => 'order.email',    'reason' => 'missing', 'message' => 'Required'],
     * //  ['field' => 'order.line.qty', 'reason' => 'invalid', 'message' => 'Too small'],
     * //  ['field' => 'order.line',     'reason' => 'invalid', 'message' => 'x']]
     * $result->toFields('order');
     * // [['field' => 'email', ...], ['field' => 'line.qty', ...], ['field' => 'line', ...]]
     * ```
     *
     * @return list<array{field: string, reason: string, message: string}>
     */
    public function toFields(?string $root = null): array
    {
        $fields = [];
        foreach ($this->errors as $name => $tree) {
            if (!is_array($tree)) {
                continue;
            }
            $segment = (string) $name;
            $kindTree = $this->kinds[$name] ?? [];
            self::collect(
                $tree,
                is_array($kindTree) ? $kindTree : [],
                $root !== null && $segment === $root ? '' : $segment,
                $fields
            );
        }

        return $fields;
    }

    /**
     * @param array<array-key, mixed> $tree
     * @param array<array-key, mixed> $kinds
     * @param list<array{field: string, reason: string, message: string}> $fields
     */
    private static function collect(array $tree, array $kinds, string $path, array &$fields): void
    {
        foreach ($tree as $key => $value) {
            $next = is_string($key)
                ? ($path === '' ? $key : $path . '.' . $key)
                : $path;

            if (is_string($value)) {
                $fields[] = [
                    'field' => $next,
                    'reason' => ($kinds[$key] ?? null) === self::KIND_MISSING
                        ? self::REASON_MISSING
                        : self::REASON_INVALID,
                    'message' => $value,
                ];
            } elseif (is_array($value)) {
                $kindSub = $kinds[$key] ?? [];
                self::collect($value, is_array($kindSub) ? $kindSub : [], $next, $fields);
            }
        }
    }
}
