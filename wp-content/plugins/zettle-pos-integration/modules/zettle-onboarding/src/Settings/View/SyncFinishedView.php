<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Onboarding\Settings\View;

use Inpsyde\Zettle\Onboarding\Settings\ButtonAction;

class SyncFinishedView implements OnboardingView
{
    use ButtonRendererTrait;

    /**
     * @var array
     */
    private $zettleProductsLink;

    /**
     * @param array $zettleProductsLink
     */
    public function __construct(array $zettleProductsLink)
    {
        $this->zettleProductsLink = $zettleProductsLink;
    }

    public function renderHeader(): string
    {
        ob_start(); ?>

        <h2>
            <?php esc_html_e('Product sync almost finished', 'zettle-pos-integration'); ?>
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
        </p>

        <?php return ob_get_clean();
    }

    public function renderProceedButton(): string
    {
        return $this->renderActionButton(ButtonAction::PROCEED, __('Complete setup', 'zettle-pos-integration'));
    }

    public function renderBackButton(): string
    {
        return '';
    }
}
