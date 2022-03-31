<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Auth\Jwt;

use Lcobucci\JWT\Signature;
use Lcobucci\JWT\Token\DataSet;

/**
 * Represents a JWT token.
 */
interface TokenInterface
{
    /**
     * Retrieves the token headers
     *
     * @return DataSet The headers.
     */
    public function getHeaders(): DataSet;

    /**
     * Retrieves the token claims.
     *
     * @return DataSet The claims.
     */
    public function getClaims(): DataSet;

    /**
     * Retrieves the token signature.
     *
     * @return Signature The signature.
     */
    public function getSignature(): Signature;

    /**
     * Retrieves an encoded string representation of the token.
     *
     * @return string The encoded string representation.
     */
    public function __toString(): string;
}
