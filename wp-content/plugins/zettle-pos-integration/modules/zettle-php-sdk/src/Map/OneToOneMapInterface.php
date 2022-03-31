<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\Map;

/**
 * Interface MapInterface
 * Maps a numeric local ID to an arbitrary string representing a remote ID
 */
interface OneToOneMapInterface extends LocalIdProvider, RemoteIdProvider
{

}
