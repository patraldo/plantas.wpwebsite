<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Metadata;

class Metadata
{
    /**
     * @var bool
     */
    private $inPos;

    /**
     * @var Source
     */
    private $source;

    /**
     * @param bool $inPos
     * @param Source $source
     */
    public function __construct(bool $inPos, Source $source)
    {
        $this->inPos = $inPos;
        $this->source = $source;
    }

    /**
     * @return bool
     */
    public function isInPos(): bool
    {
        return $this->inPos;
    }

    /**
     * @return Source
     */
    public function source(): Source
    {
        return $this->source;
    }
}
