<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Form of the "sees all" tick as an entry in `RequestAttribute::RIGHTS` / `SessionClaim::RIGHTS`.
 *
 * One entry per right: `<RESOURCE>:<right>`, where `<right>` is the ordinary right it widens, itself of the
 * form `resource:action` (e.g. `seeAll:sales.order:read`). Consumers split an entry with
 * `explode(':', $entry, 2)` (as `Permission::from` in `jardissupport/auth` does): first part = `RESOURCE`,
 * second part = the widened right, which may contain further colons.
 *
 * `RESOURCE` contains no dot, whereas real resources are named `{bc}.{agg}` — so an entry of this form never
 * matches a real right, and it always splits into exactly two parts, so no right check rejects it as malformed.
 *
 * Constants only; reading, granting and the meaning of the tick belong to the implementing packages.
 */
final class SeeAllRight
{
    /** Reserved resource of the "sees all" entry (string, no dot, no colon). */
    public const RESOURCE = 'seeAll';

    private function __construct()
    {
    }
}
