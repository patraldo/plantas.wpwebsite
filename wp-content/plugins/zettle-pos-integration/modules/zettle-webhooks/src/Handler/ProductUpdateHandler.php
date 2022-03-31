<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Webhooks\Handler;

use Inpsyde\Zettle\PhpSdk\API\Webhooks\Entity\Payload;

class ProductUpdateHandler implements WebhookHandler
{

    /**
     * @inheritDoc
     */
    public function accepts(Payload $payload): bool
    {
        return $payload->eventName() === 'ProductUpdated';
    }

    /**
     * @inheritDoc
     */
    public function handle(Payload $payload)
    {
        // Not implemented. Needs research if this can even be done safely
        // if WooCommerce should remain the absolute source of truth
    }
}
