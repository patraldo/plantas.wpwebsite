<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\Exception;

class UnexpectedBuilderPayloadTypeException extends InvalidBuilderPayloadException
{
    public function __construct(string $expectedClassName, $payload)
    {
        $actualClassName = is_object($payload) ? get_class($payload) : gettype($payload);

        parent::__construct(
            sprintf('Unexpected payload type, expected %1$s, got %2$s', $expectedClassName, $actualClassName),
            $payload,
            [ValidationErrorCodes::UNEXPECTED_PAYLOAD_TYPE]
        );
    }
}
