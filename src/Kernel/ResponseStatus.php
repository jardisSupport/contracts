<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Kernel;

/**
 * Status codes for domain responses.
 *
 * Inspired by HTTP status codes but domain-neutral.
 * Can be mapped 1:1 to HTTP codes by the API layer if needed.
 *
 * Ported 1:1 from `jardiscore/kernel` (`JardisCore\Kernel\Response\ResponseStatus`,
 * Kernel-Entkopplung contract v2): the contract is the vocabulary home generated
 * domains import from, so this enum moves here alongside {@see EventScope}.
 */
enum ResponseStatus: int
{
    case Success = 200;
    case Created = 201;
    case NoContent = 204;
    case ValidationError = 400;
    case Unauthorized = 401;
    case Forbidden = 403;
    case NotFound = 404;
    case MethodNotAllowed = 405;
    case Conflict = 409;
    case RuleViolation = 422;
    case InternalError = 500;
}
