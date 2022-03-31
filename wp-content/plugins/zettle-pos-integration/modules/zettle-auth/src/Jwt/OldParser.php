<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Auth\Jwt;

use Lcobucci\JWT\Parser;

/**
 * A decorator that standardizes the tokens from JWT v3.x.
 */
class OldParser implements ParserInterface
{
    /** @var Parser */
    protected $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @inheritDoc
     */
    public function parse(string $jwt): TokenInterface
    {
        $oldToken = $this->parser->parse($jwt);
        $newToken = new OldToken($oldToken);

        return $newToken;
    }
}
