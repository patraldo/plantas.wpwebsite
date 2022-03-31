<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Onboarding\Settings\FieldRenderer;

use Inpsyde\Zettle\Onboarding\OnboardingState as S;
use Inpsyde\Zettle\Onboarding\Settings\OnboardingStepper;
use Inpsyde\Zettle\Onboarding\Settings\View\OnboardingView;
use Inpsyde\Zettle\Settings\FieldRenderer\FieldRendererInterface;
use WC_Settings_API;

/**
 * Class OnboardingFieldRenderer
 * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 *
 * @package Inpsyde\Zettle\Onboarding\Settings
 */
class OnboardingFieldRenderer implements FieldRendererInterface
{
    /**
     * @var string
     */
    private $currentState;

    /**
     * @var OnboardingView
     */
    private $view;

    /**
     * @var OnboardingStepper
     */
    private $stepper;

    /**
     * @var callable
     */
    private $isIntegrationPage;

    /**
     * OnboardingFieldRenderer constructor.
     *
     * @param string $currentState
     * @param OnboardingView $view
     * @param OnboardingStepper $stepper
     * @param callable $isIntegrationPage
     */
    public function __construct(
        string $currentState,
        OnboardingView $view,
        OnboardingStepper $stepper,
        callable $isIntegrationPage
    ) {
        $this->view = $view;
        $this->currentState = $currentState;
        $this->stepper = $stepper;
        $this->isIntegrationPage = $isIntegrationPage;
    }

    /**
     * @param string $fieldId
     * @param array $fieldConfig
     * @param WC_Settings_API $settingsApi
     * @return bool
     */
    public function accepts(string $fieldId, array $fieldConfig, WC_Settings_API $settingsApi): bool
    {
        return array_key_exists('type', $fieldConfig) && $fieldConfig['type'] === 'zettle-onboarding';
    }

    /**
     * @param string $fieldId
     * @param array $fieldConfig
     * @param WC_Settings_API $settingsApi
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     * @return string
     */
    public function render(
        string $fieldId,
        array $fieldConfig,
        WC_Settings_API $settingsApi
    ): string {
        do_action('inpsyde.zettle.onboarding.rendering-started');

        $fieldKey = $settingsApi->get_field_key($fieldId);

        $fieldConfig = array_merge(
            [
                'title' => '',
                'disabled' => false,
                'class' => '',
                'css' => '',
                'placeholder' => '',
                'type' => 'text',
                'desc_tip' => false,
                'description' => '',
                'custom_attributes' => [],
            ],
            $fieldConfig
        );

        ob_start(); ?>

        <tr valign="top">
            <th scope="row" class="titledesc">
               <?php echo $this->renderTableHead($fieldKey, $fieldConfig, $settingsApi); // WPCS: xss ok. ?>
            </th>

            <td class="forminp">
                <div class="zettle-settings-onboarding-container">
                    <?php echo $this->renderTableContent(); // WPCS: xss ok. ?>
                </div>
            </td>
        </tr>

        <?php return ob_get_clean();
    }

    /**
     * @param string $fieldKey
     * @param array $fieldConfig
     * @param WC_Settings_API $settingsApi
     *
     * @return string
     */
    protected function renderTableHead(
        string $fieldKey,
        array $fieldConfig,
        WC_Settings_API $settingsApi
    ): string {
        ob_start(); ?>

        <div class="zettle-settings-onboarding-caption">
            <div class="zettle-settings-onboarding-caption-title">
                <?php if ($this->currentState !== S::WELCOME) : ?>
                    <label for="<?php echo esc_attr($fieldKey); ?>">
                        <?php
                            echo wp_kses_post($fieldConfig['title']);
                            echo $settingsApi->get_tooltip_html($fieldConfig); // WPCS: XSS ok.
                        ?>
                    </label>
                <?php endif; ?>
            </div>

            <?php if ($this->stepper->canRender()) : ?>
                <div class="zettle-settings-onboarding-caption-stepper">
                    <?php echo wp_kses_post($this->stepper->render()); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php return ob_get_clean();
    }

    /**
     * @return string
     */
    protected function renderTableContent(): string
    {
        ob_start(); ?>

        <div class="zettle-settings-onboarding-header">
            <?php echo $this->view->renderHeader();  // WPCS: XSS ok. ?>
        </div>

        <div class="zettle-settings-onboarding-content">
            <?php echo $this->view->renderContent();  // WPCS: XSS ok. ?>
        </div>

        <div class="zettle-settings-onboarding-actions">
            <input type="hidden" name="zettle_onboarding_state"
                   value="<?php echo esc_attr($this->currentState); ?>">

            <?php
            echo $this->view->renderProceedButton(); // WPCS: XSS ok.
            echo $this->view->renderBackButton(); // WPCS: XSS ok.
            ?>
        </div>

        <?php return ob_get_clean();
    }
}
