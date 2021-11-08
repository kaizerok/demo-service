<?php

namespace App\Jobs;

use App\Libraries\TransactionStorage\Client;
use App\Models\Country;
use App\Models\Currency;
use App\Models\ProcessorProject;
use App\Processors\ProcessorTransaction;
use App\Support\Helpers\TransactionStorageHelper;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTransactionToTransactionStorage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ProcessorTransaction $processorTransaction;

    protected array $currencies;

    //TODO
    protected $typeMap = [
        'payment',
    ];

    public int $tries = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ProcessorTransaction $processorTransaction)
    {
        $this->processorTransaction = $processorTransaction;
        $this->currencies = Currency::getCachedList()->keyBy('code')->toArray();
    }

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    public function handle()
    {
        $this->info('TS_Event', $this->processorTransaction->getType());
        //TODO
        if (!in_array($this->processorTransaction->getType(), $this->typeMap)) {
            return;
        }

        $requestData = $this->prepareRequestData();
        $this->info('TS_Request_Data', $requestData);
        try {
//            CavalryLogger::startRecording();

            $client = new Client();
            $result = $client->postTransactions($requestData);
            $this->info('TS_Result', json_decode((string)$result, true));
        } catch (Exception $e) {
            if ($e instanceof ServerException) {
                throw $e;
            }
            $this->failed($e);
        }
    }

    /**
     * Prepare request data.
     *
     * @return array
     * @throws Exception
     */
    protected function prepareRequestData()
    {
        $requestData = $this->preparePaymentCommonRequest();
        $requestData['transaction_details'] = $this->getTransactionDetails();

        if (!self::checkRequestData($requestData)) {
            throw new Exception('Unsupported request data');
        }

        return $requestData;
    }

/**
    * hidden logic
     */

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     *
     * @return void
     */
    public function failed(Exception $exception)
    {
        $this->release(60 * 30); // Retry after 30 mins

        log_event('TS_Exception', [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
        ]);
    }

    protected function info($title, $data)
    {
        log_event($title, [
            'data' => $data,
            'processorTransaction' => $this->processorTransaction->getData(),
        ]);
    }
}
