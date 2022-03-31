<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Sync\Status;

use ArrayAccess;

class StatusCodeMatcher implements ArrayAccess
{

    /**
     * @var array<string, string>
     */
    private $statusMap;

    public function __construct(array $statusMap)
    {
        $this->statusMap = $statusMap;
    }

    /**
     * Match the given status codes and return the messages
     *
     * @param string[] $statusCodes
     *
     * @return array<string, string>
     */
    public function match(array $statusCodes): array
    {
        $map = [];

        foreach ($statusCodes as $statusCode) {
            $message = $this[$statusCode];

            if (!$message) {
                $statusCode = SyncStatusCodes::UNDEFINED;
                $message = $this[$statusCode];
            }

            $map[$statusCode] = $message;
        }

        return $map;
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->statusMap[$offset] : null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->statusMap[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->statusMap[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        unset($this->statusMap[$offset]);
    }
}
