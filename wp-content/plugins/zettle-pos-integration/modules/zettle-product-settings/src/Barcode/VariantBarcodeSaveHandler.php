<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\ProductSettings\Barcode;

use Inpsyde\Zettle\PhpSdk\Repository\WooCommerce\Product\ProductRepositoryInterface;
use Inpsyde\Zettle\ProductSettings\Barcode\Repository\BarcodeSaverInterface;
use Psr\Log\LoggerInterface;
use WC_Product_Variation;

class VariantBarcodeSaveHandler
{
    /**
     * @var BarcodeSaverInterface
     */
    private $barcodeSaver;

    /**
     * @var BarcodeInputField
     */
    private $barcodeField;

    /**
     * @var ProductRepositoryInterface
     */
    private $wcProductRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        BarcodeSaverInterface $barcodeSaver,
        BarcodeInputField $barcodeField,
        ProductRepositoryInterface $wcProductRepository,
        LoggerInterface $logger
    ) {
        $this->barcodeSaver = $barcodeSaver;
        $this->barcodeField = $barcodeField;
        $this->wcProductRepository = $wcProductRepository;
        $this->logger = $logger;
    }

    public function save(int $variationId, int $variantIndex)
    {
        $variation = $this->wcProductRepository->findById($variationId);
        if (!($variation instanceof WC_Product_Variation)) {
            $this->logger->warning(sprintf(
                'Variation %1$d not found during variation settings saving.',
                $variantIndex
            ));
            return;
        }

        $barcode = $this->getBarcode($variantIndex);
        if ($barcode === null) {
            return;
        }

        $this->barcodeSaver->save($variation, $barcode);
    }

    private function getBarcode(int $variantIndex): ?string
    {
        // phpcs:ignore WordPressVIPMinimum.Security.PHPFilterFunctions.RestrictedFilter
        $barcodes = filter_input(
            INPUT_POST,
            $this->barcodeField->name(),
            FILTER_DEFAULT,
            FILTER_REQUIRE_ARRAY
        );

        if ($barcodes === false) {
            $this->logger->warning('Got incorrect barcode value during variation settings saving.');
            return null;
        }

        if (!is_array($barcodes) || !isset($barcodes[$variantIndex])) {
            return null;
        }

        return $this->sanitizeText((string) $barcodes[$variantIndex]);
    }

    private function sanitizeText(string $text): string
    {
        return sanitize_text_field(wp_unslash($text));
    }
}
