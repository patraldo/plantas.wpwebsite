<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Queue;

use Dhii\Container\ServiceProvider;
use Dhii\Modular\Module\ModuleInterface;
use Inpsyde\Queue\Bootstrap;
use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

class ZettleQueueModule implements ModuleInterface
{

    /**
     * @inheritDoc
     */
    public function setup(): ServiceProviderInterface
    {
        return new ServiceProvider(
            require __DIR__ . '/../services.php',
            require __DIR__ . '/../extensions.php'
        );
    }

    /**
     * @inheritDoc
     */
    public function run(ContainerInterface $container): void
    {
        if (!$container->has('inpsyde.queue.bootstrap')) {
            return;
        }

        /** @var Bootstrap $bootstrap */
        $bootstrap = $container->get('inpsyde.queue.bootstrap');

        add_action(
            'zettle-pos-integration.migrate',
            static function () use ($bootstrap) {
                $bootstrap->activate();
            }
        );
    }
}
