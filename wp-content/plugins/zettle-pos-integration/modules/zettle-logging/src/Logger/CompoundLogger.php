<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Logging\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\NullLogger;

class CompoundLogger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @var LoggerInterface[]
     */
    private $loggers;

    public function __construct(LoggerInterface ...$loggers)
    {
        $this->loggers = $loggers;
    }

    /**
     *  phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     *
     * @inheritDoc
     */
    public function log($level, $message, array $context = [])
    {
        foreach ($this->loggers as $logger) {
            $logger->log($level, $message, $context);
        }
    }

    public function addLogger(LoggerInterface $logger): self
    {
        if (!$logger instanceof NullLogger) {
            $this->loggers[] = $logger;
        }

        return $this;
    }
}
