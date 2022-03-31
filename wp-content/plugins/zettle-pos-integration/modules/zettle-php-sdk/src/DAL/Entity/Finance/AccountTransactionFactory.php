<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\PhpSdk\DAL\Entity\Finance;

use DateTime;
use Exception;
use Inpsyde\Zettle\PhpSdk\DAL\Entity\Finance\Type\TransactionType;

class AccountTransactionFactory
{
    /**
     * @param string $timestamp
     * @param string $amount
     * @param string $originatorTransactionType
     * @param string $originatingTransactionUuid
     *
     * @return AccountTransaction
     *
     * @throws Exception
     */
    public function create(
        string $timestamp,
        string $amount,
        string $originatorTransactionType,
        string $originatingTransactionUuid
    ): AccountTransaction {

        return new AccountTransaction(
            new DateTime($timestamp),
            (int) $amount,
            TransactionType::get($originatorTransactionType),
            $originatingTransactionUuid
        );
    }
}
