<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\ProductDebug\Listing;

class CustomColumn
{

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $name;

    public function __construct(string $key, string $name)
    {
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * Add Custom Column - if it's not existing
     *
     * @param $columns
     *
     * @return mixed
     *
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     * phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
     */
    public function add($columns)
    {
        if (!isset($columns[$this->key])) {
            $columns[$this->key] = $this->name;
        }

        return $columns;
    }

    /**
     * @param string $name
     * @param int $productId
     *
     * @return string
     */
    public function renderContent(string $name, int $productId): string
    {
        if ($name !== $this->key) {
            return '';
        }

        return sprintf(
            '<div data-sync-status="true" data-sync-status-id="%d">%s</div>',
            $productId,
            $this->renderLoader()
        );
    }

    /**
     * Wrapper Function to return loading spinner
     *
     * @return string
     */
    private function renderLoader(): string
    {
        return '<div class="loader">
            <div class="loader-circle1 loader-circle"></div>
            <div class="loader-circle2 loader-circle"></div>
            <div class="loader-circle3 loader-circle"></div>
            <div class="loader-circle4 loader-circle"></div>
            <div class="loader-circle5 loader-circle"></div>
            <div class="loader-circle6 loader-circle"></div>
            <div class="loader-circle7 loader-circle"></div>
            <div class="loader-circle8 loader-circle"></div>
            <div class="loader-circle9 loader-circle"></div>
            <div class="loader-circle10 loader-circle"></div>
            <div class="loader-circle11 loader-circle"></div>
            <div class="loader-circle12 loader-circle"></div>
        </div>';
    }
}
