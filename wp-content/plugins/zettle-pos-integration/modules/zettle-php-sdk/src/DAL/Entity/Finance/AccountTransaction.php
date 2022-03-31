<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Finance;

use DateTime;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Finance\Type\TransactionType;

final class AccountTransaction
{
    /**
     * @var DateTime
     */
    private $timestamp;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var TransactionType
     */
    private $originatorTransactionType;

    /**
     * @var string
     */
    private $originatingTransactionUuid;

    /**
     * AccountTransaction constructor.
     *
     * @param DateTime $timestamp
     * @param int $amount
     * @param TransactionType $originatorTransactionType
     * @param string $originatingTransactionUuid
     */
    public function __construct(
        DateTime $timestamp,
        int $amount,
        TransactionType $originatorTransactionType,
        string $originatingTransactionUuid
    ) {

        $this->timestamp = $timestamp;
        $this->amount = $amount;
        $this->originatorTransactionType = $originatorTransactionType;
        $this->originatingTransactionUuid = $originatingTransactionUuid;
    }

    /**
     * @return DateTime
     */
    public function timestamp(): DateTime
    {
        return $this->timestamp;
    }

    /**
     * @return int
     */
    public function amount(): int
    {
        return $this->amount;
    }

    /**
     * @return TransactionType
     */
    public function originatorTransactionType(): TransactionType
    {
        return $this->originatorTransactionType;
    }

    /**
     * @return string
     */
    public function originatingTransactionUuid(): string
    {
        return $this->originatingTransactionUuid;
    }
}
