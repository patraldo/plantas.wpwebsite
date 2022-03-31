<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Onboarding\Settings\View;

class OnboardingCompletedView implements OnboardingView
{
    use ButtonRendererTrait;

    /**
     * @var array
     */
    private $zettleProductsLink;

    /**
     * @var array
     */
    private $settingsLink;

    /**
     * @param array $zettleProductsLink
     */
    public function __construct(array $zettleProductsLink, array $settingsLink)
    {
        $this->zettleProductsLink = $zettleProductsLink;
        $this->settingsLink = $settingsLink;
    }

    public function renderHeader(): string
    {
        ob_start(); ?>

        <h2>
            <?php esc_html_e('WooCommerce is connected to PayPal Zettle', 'zettle-pos-integration'); ?>
        </h2>

        <?php return ob_get_clean();
    }

    public function renderContent(): string
    {
        ob_start() ?>

        <p>
            <?php esc_html_e(
                'In the future, when you make a sale or edit stock, we will update your stock in PayPal Zettle and WooCommerce.',
                'zettle-pos-integration'
            ) ?>
        </p>

        <p>
            <?php esc_html_e(
                'If you need to update your products, do this in WooCommerce and they\'ll sync to your PayPal Zettle app automatically.',
                'zettle-pos-integration'
            ) ?>
        </p>

        <p>
            <a class="link"
                rel="noopener noreferrer"
                target="_blank"
                href="<?php echo esc_url_raw($this->zettleProductsLink['url']); ?>">
                <?php echo esc_html($this->zettleProductsLink['title']); ?></a>

            <?php if (!filter_input(INPUT_GET, 'review', FILTER_VALIDATE_BOOL)) : ?>
            <span class="separator">|</span>
                <a class="link"
                    href="<?php echo esc_url_raw($this->settingsLink['url']); ?>">
                    <?php echo esc_html($this->settingsLink['title']); ?></a>

            <?php endif; ?>
        </p>

        <?php return ob_get_clean();
    }

    public function renderProceedButton(): string
    {
        return '';
    }

    public function renderBackButton(): string
    {
        return '';
    }
}
