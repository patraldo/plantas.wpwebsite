<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\API\Webhooks\Entity;

interface Payload
{

    /**
     * @return string
     */
    public function eventName(): string;

    /**
     * @return string
     */
    public function organizationUuid(): string;

    /**
     * @return string
     */
    public function messageId(): string;

    /**
     * @return array<string, mixed>
     */
    public function payload(): array;
}
