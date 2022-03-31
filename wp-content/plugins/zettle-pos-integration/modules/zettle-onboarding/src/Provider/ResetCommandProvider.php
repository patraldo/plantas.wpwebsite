<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Onboarding\Provider;

use Exception;
use Inpsyde\Zettle\Onboarding\Cli\ResetOnboardingCommand;
use Inpsyde\Zettle\Provider;
use Psr\Container\ContainerInterface as C;
use WP_CLI;

class ResetCommandProvider implements Provider
{

    /**
     * @var ResetOnboardingCommand
     */
    private $resetOnboardingCommand;

    /**
     * CliCommandProvider constructor.
     *
     * @param ResetOnboardingCommand $resetOnboardingCommand
     */
    public function __construct(ResetOnboardingCommand $resetOnboardingCommand)
    {
        $this->resetOnboardingCommand = $resetOnboardingCommand;
    }

    /**
     * @inheritDoc
     */
    public function boot(C $container): bool
    {
        if (defined('WP_CLI') && WP_CLI) {
            try {
                WP_CLI::add_command(
                    'zettle reset onboarding',
                    $this->resetOnboardingCommand
                );
            } catch (Exception $exception) {
            }
        }

        return true;
    }
}
