<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Auth\Jwt;

use Lcobucci\JWT\Signature;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\DataSet;

class OldToken implements TokenInterface
{
    /** @var Token */
    protected $token;

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    public function getHeaders(): DataSet
    {
        return $this->token->headers();
    }

    public function getClaims(): DataSet
    {
        return $this->token->claims();
    }

    public function getSignature(): Signature
    {
        return $this->token->signature();
    }

    public function __toString(): string
    {
        return $this->token->toString();
    }
}
