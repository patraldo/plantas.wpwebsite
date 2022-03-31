<?php

namespace Inpsyde\Zettle\Auth\Validator;

/**
 * The interface for JWT validation.
 */
interface ValidatorInterface
{
    /**
     * Validates the JWT.
     *
     * @param string $jwt
     * @return bool
     */
    public function validate(string $jwt): bool;
}
