<?php

namespace App\Support\Helpers;

use App\Jobs\CreateLedgerTransaction;
use App\Jobs\SendTransactionToTransactionStorage;
use App\Models\Country;
use App\Models\Entity;
use App\Models\ProcessorProject;
use App\Models\Transaction;
use App\Processors\ProcessorTransaction;
use App\Services\EntityRolesService;
use Exception;

/**
 * @package App\Support\Helpers
 */
class TransactionProcessor
{
    private ProcessorProject $processorProject;
    private array $webhookData;

    /**
     * TransactionProcessor constructor.
     *
     * @param ProcessorProject $processorProject
     * @param array            $webhookData
     */
    public function __construct(
        ProcessorProject $processorProject,
        array $webhookData
    ) {
        $this->processorProject = $processorProject;
        $this->webhookData = $webhookData;
    }

    /**
     * @throws Exception
     */
    public function process()
    {
        $processorTransaction = $this->processorProject->getProcessor()->createTransaction($this->webhookData);

        self::sendTransactionToTransactionStorage($processorTransaction);

            /**
            * hidden logic
            */

            $transaction = self::prepareTransaction(hidden);

            $ledgerConfig = ['ledger_endpoint_key' => $entity->getCode()];
            CreateLedgerTransaction::dispatch($transaction->toArray(), $ledgerConfig);

            log_event('Ledger_transaction', ['transaction' => $transaction, 'data' => $ledgerConfig]);
        }
    }

    /**
     * @param ProcessorTransaction $processorTransaction
     */
    private static function sendTransactionToTransactionStorage(ProcessorTransaction $processorTransaction): void
    {
        if (config('services.transaction_storage.enable')) {
            SendTransactionToTransactionStorage::dispatch($processorTransaction);
        }
    }

    /**
     * @param     $value
     * @param int $precision
     *
     * @return float
     */
    private static function round($value, $precision = 4)
    {
        return round($value, $precision);
    }

    /**
     * @param Entity $taxEntity
     *
     * @return bool
     */
    public static function isMultiEntitiesStrategy(Entity $taxEntity)
    {
        return !$taxEntity->isMaster();
    }

    /**
     * @param Entity $taxEntity
     * @param Entity $masterEntity
     *
     * @return array
     */
    public static function getPaymentEntities(Entity $taxEntity, Entity $masterEntity)
    {
        return array_filter([
            $taxEntity,
            (self::isMultiEntitiesStrategy($taxEntity)) ? $masterEntity : null
        ]);
    }

    /**
     * @return array
     */
    private static function getDefaultExtraData(): array
    {
        return [
            'payout' => [
                'mode' => 'base_on_ar' //todo: move to merchant setting
            ]
        ];
    }

    /**
     * todo: support a case when tax amount should be not in processing currency
     *
     * @param $extraData
     * @param $key
     * @param $gov
     * @param $amount
     * @param $currency
     * @param $payout_currency
     */
    private static function fillExtraData(&$extraData, $key, $gov, $amount, $currency)
    {
        $extraData[$key] = compact('gov', 'amount', 'currency');
    }
}
