<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\API\Webhooks\Entity;

use Psr\Http\Message\UriInterface;

class ZettleWebhook implements Webhook
{

    const TRANSPORT_NAME = 'WEBHOOK';

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var array
     */
    private $eventNames;

    /**
     * @var UriInterface
     */
    private $destination;

    /**
     * @var string
     */
    private $contactEmail;

    public function __construct(
        string $uuid,
        array $eventNames,
        UriInterface $destination,
        string $contactEmail
    ) {
        $this->uuid = $uuid;
        $this->eventNames = $eventNames;
        $this->destination = $destination;
        $this->contactEmail = $contactEmail;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function contactEmail(): string
    {
        return $this->contactEmail;
    }

    public function destination(): UriInterface
    {
        return $this->destination;
    }

    public function eventNames(): array
    {
        return $this->eventNames;
    }

    /**
     * @param array $eventNames
     */
    public function changeEventNames(array $eventNames): void
    {
        $this->eventNames = $eventNames;
    }
}
