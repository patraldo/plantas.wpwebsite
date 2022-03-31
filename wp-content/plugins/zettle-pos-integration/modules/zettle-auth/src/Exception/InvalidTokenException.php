<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Auth\Exception;

use Exception;
use Psr\Http\Client\ClientExceptionInterface;

class InvalidTokenException extends Exception implements ClientExceptionInterface
{

}
