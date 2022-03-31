<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Onboarding\Settings\Filter;

interface SettingsFilter
{
    /**
     * @param array $settings
     *
     * @return array
     */
    public function filter(array $settings): array;
}
