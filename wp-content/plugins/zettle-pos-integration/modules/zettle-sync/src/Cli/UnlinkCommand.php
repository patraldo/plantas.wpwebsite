<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Sync\Cli;

use Inpsyde\Queue\Queue\Job\Context;
use Inpsyde\Queue\Queue\Job\EphemeralJobRepository;
use Inpsyde\Queue\Queue\Job\Job;
use Psr\Log\LoggerInterface;
use WP_CLI;

class UnlinkCommand
{

    /**
     * @var Job
     */
    private $unlinkProductJob;

    /**
     * @var Job
     */
    private $unlinkVariantJob;

    /**
     * @var Job
     */
    private $unlinkImagesJob;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UnlinkCommand constructor.
     *
     * @param Job $unlinkProductJob
     * @param Job $unlinkVariantJob
     * @param Job $unlinkImagesJob
     * @param LoggerInterface $logger
     */
    public function __construct(
        Job $unlinkProductJob,
        Job $unlinkVariantJob,
        Job $unlinkImagesJob,
        LoggerInterface $logger
    ) {
        $this->unlinkProductJob = $unlinkProductJob;
        $this->unlinkVariantJob = $unlinkVariantJob;
        $this->unlinkImagesJob = $unlinkImagesJob;
        $this->logger = $logger;
    }

    /**
     * Sync a single product
     *
     * ## OPTIONS
     *
     * <id>
     * : The WC_Product ID
     *
     * ## EXAMPLES
     *
     *     wp zettle unlink product
     *
     * @when after_wp_load
     */
    public function product(array $args, array $assocArgs)
    {
        $context = Context::fromArray(
            [
                'localId' => (int) $args[0],
            ]
        );

        $this->unlinkProductJob->execute($context, new EphemeralJobRepository(), $this->logger);
    }

    /**
     * Unlink a single variation
     *
     * ## OPTIONS
     *
     * <id>
     * : The WC_Product_Variation ID
     *
     * ## EXAMPLES
     *
     *     wp zettle unlink variant
     *
     * @when after_wp_load
     */
    public function variant(array $args, array $assocArgs)
    {
        $context = Context::fromArray(
            [
                'variantId' => (int) $args[0],
            ]
        );

        $this->unlinkVariantJob->execute(
            $context,
            new EphemeralJobRepository(),
            $this->logger
        );
    }

    /**
     * Unlink images from product or variation
     *
     * ## OPTIONS
     *
     * <id>
     * : The WC_Product or WC_Product_Variation ID
     *
     * ## EXAMPLES
     *
     *     wp zettle unlink images
     *
     * @when after_wp_load
     */
    public function images(array $args, array $assocArgs)
    {
        $context = Context::fromArray(
            [
                'productId' => (int) $args[0],
            ]
        );

        $this->unlinkImagesJob->execute(
            $context,
            new EphemeralJobRepository(),
            $this->logger
        );
    }

    /**
     * Experimental: Unlink images from attachmentId and type
     *
     * ## OPTIONS
     *
     * <id>
     * : The Attachment ID
     *
     * <type>
     * : The mapped entity (product or variant)
     *
     * ## EXAMPLES
     *
     *     wp zettle unlink image
     *
     * @when after_wp_load
     */
    public function image(array $args, array $assocArgs)
    {
        $context = Context::fromArray(
            [
                'attachmentId' => (int) $args[0],
                'type' => (string) $args[1],
            ]
        );

        $this->unlinkImagesJob->execute(
            $context,
            new EphemeralJobRepository(),
            $this->logger
        );
    }
}
