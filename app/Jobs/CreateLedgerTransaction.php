<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Support\Helpers\Ledger;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class CreateLedgerTransaction
 *
 * @package App\Jobs
 */
class CreateLedgerTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    private array $transactionData;
    private array $ledgerConfig;

    public function __construct(array $transactionData, array $ledgerConfig)
    {
        $this->transactionData = $transactionData;
        $this->ledgerConfig = $ledgerConfig;
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $ledgerService = new Ledger($this->ledgerConfig);
        $transaction = new Transaction($this->transactionData);

        $result = $ledgerService->createTransaction($transaction);
        //TODO: store $result
    }
}
