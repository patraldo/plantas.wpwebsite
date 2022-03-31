<?php

namespace Inpsyde\Zettle\Auth\Jwt;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Parsing\Decoder;

class OldParserFactory implements ParserFactoryInterface
{
    /** @var Decoder */
    protected $decoder;

    public function __construct(Decoder $decoder)
    {
        $this->decoder = $decoder;
    }

    /**
     * @inheritDoc
     */
    public function createParser(): ParserInterface
    {
        return new OldParser(new Parser());
    }
}
