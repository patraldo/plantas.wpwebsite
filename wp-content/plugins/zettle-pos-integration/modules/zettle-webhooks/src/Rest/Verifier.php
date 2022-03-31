<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Webhooks\Rest;

use WP_REST_Request;

interface Verifier
{

    /**
     * @param WP_REST_Request $request
     *
     * @return bool
     */
    public function verify(WP_REST_Request $request): bool;
}
