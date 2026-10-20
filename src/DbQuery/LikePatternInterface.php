<?php

declare(strict_types=1);

namespace JardisSupport\Contract\DbQuery;

/**
 * A LIKE pattern built from a literal search term
 *
 * Accepted by `DbComparisonOperatorsInterface::like()` / `notLike()` in place of a
 * plain string. The implementation escapes the wildcard characters of the term, so a
 * literal `%` or `_` in the search term keeps no wildcard meaning. The condition
 * binds `pattern()` as the value and appends `escapeClause()` behind it.
 *
 * Example:
 * ```php
 * $query->where('name')->like($pattern);   // name LIKE ? ESCAPE '!'
 * ```
 */
interface LikePatternInterface
{
    /**
     * The already escaped pattern, ready to be bound as the LIKE value
     *
     * @return string The pattern including its wildcards
     */
    public function pattern(): string;

    /**
     * The clause appended behind the bound pattern, e.g. ` ESCAPE '!'`
     *
     * @return string The SQL ESCAPE clause (with a leading space)
     */
    public function escapeClause(): string;
}
