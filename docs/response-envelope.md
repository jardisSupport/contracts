# DomainResponse Envelope Contract

Transport-neutral specification of the JSON envelope every Jardis domain
response is mapped into. It is fixed here, in `jardissupport/contract`, so
that **any** transport layer — the reference `jardiscore/app` HTTP mapper,
a hand-rolled CLI/worker adapter, or a third-party framework integration —
can implement the same client-facing contract without importing
`jardiscore/app`. One error/response format for every client, independent of
which package produced it.

The status vocabulary (`ResponseStatus`, see below) already lives in this
package. This document adds the envelope shape around it: the JSON body
structure, the status-code ladder, the `422` rule-violation payload, and two
documented exceptions (object coercion, the `204` no-body case).

---

## Envelope shape

Every response — success or error, domain-produced or boundary-produced —
is serialized as a single JSON object with exactly these four top-level
keys:

```json
{
  "status": 200,
  "data": {},
  "errors": {},
  "meta": {}
}
```

| Key      | Type              | Source                                                   |
|----------|-------------------|-----------------------------------------------------------|
| `status` | `int`              | `ResponseStatus` case value (`getStatus()`)               |
| `data`   | `object`           | `DomainResponseInterface::getData()`                       |
| `errors` | `object`           | `DomainResponseInterface::getErrors()`                      |
| `meta`   | `object`           | `DomainResponseInterface::getMetadata()`                    |

`data`, `errors` and `meta` are always JSON **objects**, never arrays — see
the Object-Coercion Rule below.

---

## Status ladder

`JardisSupport\Contract\Kernel\ResponseStatus` is the single source of truth
for the status vocabulary. Every case below MUST be representable in the
envelope; a consistency check (see the package's `make` targets) keeps this
table and the enum in sync.

| Case               | HTTP code | Meaning                                                                 |
|--------------------|-----------|--------------------------------------------------------------------------|
| `Success`          | 200       | Successful read or command, no resource created                          |
| `Created`          | 201       | Command created a new resource                                           |
| `NoContent`        | 204       | Successful, no response body (see the 204 Exception below)               |
| `ValidationError`  | 400       | Malformed or invalid request (e.g. syntactically invalid JSON body)       |
| `Unauthorized`     | 401       | Missing or invalid authentication                                        |
| `Forbidden`        | 403       | Authenticated, but not permitted                                         |
| `NotFound`         | 404       | No matching route or resource — also the boundary "no route" case        |
| `MethodNotAllowed` | 405       | Route exists, but not for the requested HTTP method (see Allow-Header)   |
| `Conflict`         | 409       | Conflicting state (e.g. version/uniqueness conflict)                      |
| `RuleViolation`    | 422       | A guarded business Rule rejected the command (see 422 Payload below)     |
| `InternalError`    | 500       | Unexpected failure; response body is generic, see Boundary Errors below  |

---

## 422 payload: `{rule, messageKey, context}`

When `status` is `RuleViolation` (422), `data` carries the rejection payload
unchanged, with exactly these three keys:

```json
{
  "status": 422,
  "data": {
    "rule": "OrderMustNotBeShipped",
    "messageKey": "order.already_shipped",
    "context": { "orderId": "42" }
  },
  "errors": {},
  "meta": {}
}
```

| Field        | Meaning                                                                 |
|--------------|----------------------------------------------------------------------------|
| `rule`       | Identifier of the Rule class that rejected the command                    |
| `messageKey` | Stable, translatable message key — part of the API, stable across Rule versions |
| `context`    | Structured detail data for the rejection (arbitrary JSON-serializable object) |

A technical failure while evaluating a Rule (e.g. a repository read fails)
is **not** a `RuleViolation` — it surfaces as `InternalError` (500), never
422.

---

## Object-Coercion Rule

`DomainResponseInterface::getData()` / `getErrors()` / `getMetadata()` are
typed as PHP `array`. An empty PHP array (`[]`) is ambiguous in JSON — it
serializes as a JSON array, not an object. Every transport-layer mapper
implementing this contract MUST explicitly coerce an empty `data`/`errors`/
`meta` array to a JSON **object** (`{}`), never leave it as `[]`. Non-empty
associative arrays already serialize as JSON objects and need no coercion.

---

## 204 exception: no body

`NoContent` (204) responses carry **no response body at all** — this is an
HTTP-spec requirement (a 204 response must not have a message body), not a
contract-schema choice. This is the one documented exception to the envelope
shape above: a 204 response is not `{"status":204,"data":{},...}`, it is an
empty body with the 204 status line.

---

## Allow-header format (405)

A `MethodNotAllowed` (405) response additionally carries an `Allow` HTTP
header listing every HTTP method registered for the matched route, per
RFC 7231 §7.4.1: a single header value, methods separated by `, ` (comma +
space), e.g.:

```
Allow: GET, POST, PATCH
```

---

## Boundary errors (404 / 405) use the same envelope

`404` (no matching route) and `405` (method not allowed) can occur **before**
any domain code runs — no `BoundedContext`/`Context` was ever invoked. These
boundary errors are not exempt from this contract: they answer in the exact
same JSON envelope schema as a domain-produced error, so a client never has
to distinguish "domain said 404" from "no route matched" by response shape.
The only addition for 405 is the `Allow` header above.

`500` (an exception escaping before a domain result was produced) follows
the same rule: same envelope shape, generic `data`/`errors` content — no
exception message, stack trace, or class name leaks into the response body.
Full error detail belongs in the server-side log, not the client response.

---

## Non-goals of this document

This document fixes the **client-facing contract**: envelope shape, status
ladder, the two documented exceptions, and the 405 `Allow` format. It does
not prescribe:

- how a transport layer obtains a `DomainResponseInterface` (that is
  `jardiscore/kernel` + generated domain code),
- how routing, middleware, or bootstrap work (transport-layer concern),
- a specific implementation — `jardiscore/app` is the reference mapper for
  this contract, not a requirement to depend on it.
