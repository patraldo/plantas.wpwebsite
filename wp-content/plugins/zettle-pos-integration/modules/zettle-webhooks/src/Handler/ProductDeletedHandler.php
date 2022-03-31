<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Webhooks\Handler;

use Inpsyde\Queue\Queue\Job\Context;
use Inpsyde\Queue\Queue\Job\EphemeralJobRepository;
use Inpsyde\Zettle\PhpSdk\API\Webhooks\Entity\Payload;
use Inpsyde\Zettle\Sync\Job\UnlinkProductJob;
use Psr\Log\LoggerInterface;

class ProductDeletedHandler implements WebhookHandler
{

    /**
     * @var UnlinkProductJob
     */
    private $unlinkProductJob;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        UnlinkProductJob $unlinkProductJob,
        LoggerInterface $logger
    ) {
        $this->unlinkProductJob = $unlinkProductJob;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function accepts(Payload $payload): bool
    {
        return $payload->eventName() === 'ProductDeleted';
    }

    /**
     * @inheritDoc
     */
    public function handle(Payload $payload)
    {
        $productData = $payload->payload();
        $this->logger->info(
            sprintf(
                'Attempting to unlink product %s',
                $productData['uuid']
            )
        );
        $this->unlinkProductJob->execute(
            Context::fromArray(
                [
                    'remoteId' => $productData['uuid'],
                ]
            ),
            new EphemeralJobRepository(),
            $this->logger
        );
    }
}
