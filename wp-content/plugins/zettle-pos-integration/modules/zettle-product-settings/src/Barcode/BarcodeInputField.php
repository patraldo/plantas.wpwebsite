<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\ProductSettings\Barcode;

use Inpsyde\Zettle\ProductSettings\Barcode\Repository\BarcodeRetrieverInterface;
use WC_Product;

class BarcodeInputField
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var BarcodeRetrieverInterface
     */
    private $barcodeRetriever;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $containerClasses;

    /**
     * @param string $containerClasses Additional CSS classes for the container of input field.
     */
    public function __construct(
        string $name,
        BarcodeRetrieverInterface $barcodeRetriever,
        string $label,
        string $containerClasses = ''
    ) {
        $this->name = $name;
        $this->barcodeRetriever = $barcodeRetriever;
        $this->label = $label;
        $this->containerClasses = $containerClasses;
    }

    // phpcs:ignore Inpsyde.CodeQuality.FunctionLength.TooLong
    public function render(WC_Product $product, ?int $index = null): string
    {
        $name = $this->name . ($index !== null ? "[${index}]" : '');
        $id = $this->name . ($index !== null ? $index : '');

        $currentBarcode = $this->barcodeRetriever->get($product) ?? '';

        ob_start(); ?>

        <div class="zettle-barcode-input">
            <p class="zettle-barcode-input-field <?= esc_attr($this->containerClasses) ?>">
                <label for="<?= esc_attr($id) ?>"><?= esc_html($this->label) ?></label>
                <span>
                    <input id="<?= esc_attr($id) ?>"
                           type="text"
                           name="<?= esc_attr($name) ?>"
                           value="<?= esc_attr($currentBarcode) ?>">
                    <button
                            type="button"
                            aria-label="<?= esc_attr__('Scan barcode', 'zettle-pos-integration') ?>">
                        📷
                    </button>
                </span>
            </p>

            <div class="zettle-barcode-scan" style="display: none">
                <div>
                    <label><?= esc_html__('Barcode type', 'zettle-pos-integration') ?>
                        <select name="barcode_type">
                            <option value="ean_extended,ean,ean_8,code_128,code_39" selected="selected">
                                <?= esc_html__('EAN (13, 8, extended), Code 128, Code 39', 'zettle-pos-integration') ?>
                            </option>
                            <option value="code_128">Code 128</option>
                            <option value="code_39">Code 39</option>
                            <option value="code_39_vin">Code 39 VIN</option>
                            <option value="ean">EAN-13</option>
                            <option value="ean_extended"><?= esc_html__('EAN extended', 'zettle-pos-integration') ?></option>
                            <option value="ean_8">EAN-8</option>
                            <option value="upc">UPC-A</option>
                            <option value="upc_e">UPC-E</option>
                            <option value="codabar">Codabar</option>
                            <option value="i2of5"><?= esc_html__('I2 of 5', 'zettle-pos-integration') ?></option>
                            <option value="2of5"><?= esc_html__('Standard 2 of 5', 'zettle-pos-integration') ?></option>
                            <option value="code_93">Code 93</option>
                        </select>
                    </label>
                </div>
                <div>
                    <label><?= esc_html__('Camera', 'zettle-pos-integration') ?>
                        <select name="camera">
                        </select>
                    </label>
                </div>

                <div class="zettle-barcode-scanner-viewport">
                </div>
            </div>
        </div>

        <?php return ob_get_clean();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}
