<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Onboarding\Settings\FieldRenderer;

use Inpsyde\Zettle\Settings\FieldRenderer\FieldRendererInterface;
use WC_Settings_API;

/**
 * A password field that does not expose the currently saved value in HTML.
 */
class WriteOnlyPasswordFieldRenderer implements FieldRendererInterface
{
    public const DEFAULT_PLACEHOLDER = '**********';

    /**
     * @var string
     */
    protected $placeholder;

    /**
     * @param string $placeholder The value put into the field before editing.
     */
    public function __construct(string $placeholder = self::DEFAULT_PLACEHOLDER)
    {
        $this->placeholder = $placeholder;
    }

    public function accepts(string $fieldId, array $fieldConfig, WC_Settings_API $settingsApi): bool
    {
        return ($fieldConfig['type'] ?? '') === 'zettle-writeonly-password';
    }

    public function render(string $fieldId, array $fieldConfig, WC_Settings_API $settingsApi): string
    {
        $value = $settingsApi->get_option($fieldId);

        $html = $settingsApi->generate_password_html($fieldId, $fieldConfig);
        if ($value) {
            $html = str_ireplace("value=\"$value\"", "value=\"{$this->placeholder}\"", $html);
        }

        return $html;
    }
}
