<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * The catalog of all rights a domain knows, each bound to the endpoint it protects.
 *
 * Consumers: the generated `{Domain}Rights` of the Builder implements it, and the follow-up
 * "role management" reads only this interface to list the assignable rights. The package knows no
 * right of its own; the entries are data of the implementing domain.
 *
 * @deprecated 2.17.0 Recht je Route: `RequireRight` in `jardiscore/app` 3.0.0; Entfernung in contracts 3
 */
interface RightsCatalogInterface
{
    /**
     * All rights of the domain in endpoint order; keys are unique.
     *
     * @return array<string, string> right => endpoint, e.g.
     *         `'fulfillment.shipment:read' => 'GET /fulfillment/shipments/queries/byIdentifiers'`
     */
    public function rights(): array;
}
