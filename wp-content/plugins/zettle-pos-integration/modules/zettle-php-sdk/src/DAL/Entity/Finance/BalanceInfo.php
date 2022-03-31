<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Finance;

class BalanceInfo
{
    /**
     * @var float
     */
    private $totalBalance;

    /**
     * @var string
     */
    private $currencyId;

    /**
     * BalanceInfo constructor.
     * @param float $totalBalance
     * @param string $currencyId
     */
    public function __construct(float $totalBalance, string $currencyId)
    {
        $this->totalBalance = $totalBalance;
        $this->currencyId = $currencyId;
    }

    /**
     * @return float
     */
    public function totalBalance(): float
    {
        return $this->totalBalance;
    }

    /**
     * @return string
     */
    public function currencyId(): string
    {
        return $this->currencyId;
    }
}
