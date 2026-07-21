<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Bin;

/**
 * Checks that the status ladder documented in docs/response-envelope.md
 * matches the actual `ResponseStatus` enum cases 1:1 (same case names, same
 * HTTP-code values, no case missing on either side).
 *
 * The contract package intentionally carries no implementation code in
 * `src/` (interfaces, enums, value objects and exceptions only — see
 * README.md "Design Principles"), so this drift check lives here as a
 * self-contained script under `bin/`, not as a Handler class in `src/`.
 * Namespaced (rather than global functions) to avoid polluting the global
 * namespace from a script that is never autoloaded.
 *
 * Exits 0 and prints an OK line when the enum and the doc table agree.
 * Exits 1 and prints every mismatch when they don't.
 *
 * Usage:
 *   php bin/check-envelope-consistency.php
 */

require __DIR__ . '/../vendor/autoload.php';

use JardisSupport\Contract\Kernel\ResponseStatus;
use RuntimeException;

/**
 * @return array<string, int> case name => HTTP-code value, as declared on the enum.
 */
function enumCases(): array
{
    $result = [];
    foreach (ResponseStatus::cases() as $case) {
        $result[$case->name] = $case->value;
    }
    return $result;
}

/**
 * @return array<string, int> case name => HTTP-code value, as documented in the
 *  "## Status ladder" table of docs/response-envelope.md.
 *
 * @throws RuntimeException when the doc file is unreadable or the section is missing.
 */
function documentedCases(string $docPath): array
{
    $contents = file_get_contents($docPath);
    if ($contents === false) {
        throw new RuntimeException("cannot read {$docPath}");
    }

    $section = extractStatusLadderSection($contents);

    $result = [];
    foreach (explode("\n", $section) as $line) {
        if (preg_match('/^\|\s*`([A-Za-z0-9]+)`\s*\|\s*(\d{3})\s*\|/', $line, $matches) === 1) {
            $result[$matches[1]] = (int) $matches[2];
        }
    }
    return $result;
}

/**
 * @throws RuntimeException when the "## Status ladder" heading is missing.
 */
function extractStatusLadderSection(string $contents): string
{
    $headingPos = strpos($contents, '## Status ladder');
    if ($headingPos === false) {
        throw new RuntimeException("'## Status ladder' heading not found in doc");
    }

    $rest = substr($contents, $headingPos + strlen('## Status ladder'));
    $nextHeadingPos = strpos($rest, "\n## ");
    return $nextHeadingPos === false ? $rest : substr($rest, 0, $nextHeadingPos);
}

/**
 * @param array<string, int> $fromEnum
 * @param array<string, int> $fromDoc
 * @return list<string> human-readable mismatch descriptions, empty when consistent.
 */
function diff(array $fromEnum, array $fromDoc): array
{
    $problems = [];

    $missingInDoc = array_diff_key($fromEnum, $fromDoc);
    if ($missingInDoc !== []) {
        $problems[] = 'Cases missing from the doc table: ' . implode(', ', array_keys($missingInDoc));
    }

    $missingInEnum = array_diff_key($fromDoc, $fromEnum);
    if ($missingInEnum !== []) {
        $problems[] = 'Doc rows without a matching enum case: ' . implode(', ', array_keys($missingInEnum));
    }

    $valueMismatches = [];
    foreach (array_intersect_key($fromEnum, $fromDoc) as $name => $value) {
        if ($fromDoc[$name] !== $value) {
            $valueMismatches[] = sprintf('%s: enum=%d doc=%d', $name, $value, $fromDoc[$name]);
        }
    }
    if ($valueMismatches !== []) {
        $problems[] = 'HTTP-code mismatches: ' . implode('; ', $valueMismatches);
    }

    return $problems;
}

$docPath = dirname(__DIR__) . '/docs/response-envelope.md';

try {
    $fromEnum = enumCases();
    $fromDoc = documentedCases($docPath);
} catch (RuntimeException $exception) {
    fwrite(STDERR, "[envelope-consistency] FAILED: {$exception->getMessage()}\n");
    exit(1);
}

$problems = diff($fromEnum, $fromDoc);

if ($problems !== []) {
    fwrite(STDERR, "[envelope-consistency] FAILED: ResponseStatus enum and docs/response-envelope.md disagree.\n");
    foreach ($problems as $problem) {
        fwrite(STDERR, "  {$problem}\n");
    }
    exit(1);
}

fwrite(STDOUT, sprintf(
    "[envelope-consistency] OK: %d ResponseStatus cases match docs/response-envelope.md.\n",
    count($fromEnum)
));
