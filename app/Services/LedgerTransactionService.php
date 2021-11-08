<?php


namespace App\Services;

use App\Models\Country;
use App\Models\ProcessorWebhook;
use App\Processors\ProcessorAbstract;
use App\Support\Helpers\TransactionProcessor;

class LedgerTransactionService
{
    /**
     * @var array|mixed
     */
    private $transactionData;

    /**
     * LedgerTransactionService constructor.
     *
     * @param $reference
     */
    public function __construct($reference)
    {
        $this->transactionData = self::getTransactionDataByReference($reference);
    }

    /**
     * @return array|mixed
     */
    public function getTransactionData()
    {
        return $this->transactionData;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getPaymentEntities()
    {
        $data = $this->getTransactionData();

        $paymentSystem = ProcessorAbstract::getPaymentSystem($data);
        $userCountry = Country::findByCode($data['country']);

        $entityRolesService = new EntityRolesService($paymentSystem, $userCountry);

        return TransactionProcessor::getPaymentEntities(
            $entityRolesService->getTaxEntity(),
            $entityRolesService->getMasterEntity()
        );
    }

    /**
     * @param $reference
     *
     * @return array|mixed
     */
    protected static function getTransactionDataByReference($reference)
    {
        //TODO get data from transaction storage

        return ProcessorWebhook::getDataByReference($reference);
    }

}
