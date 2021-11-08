<?php

namespace App\Processors;

use App\Events\PaymentEvent;
use App\Exceptions\ErrorException;
use App\Models\ProcessorProject;
use App\Models\ProcessorWebhook;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class Paymentwall extends ProcessorAbstract implements Processor
{
    const ID = 1;
    const SYSTEM_NAME = 'paymentwall';

    const WEBHOOK_PARAMETERS = [
        'type' => 'required|string',
        'reference' => 'required|string',
        'project_id' => 'required|string',
        'country' => 'required|string',
        'currency' => 'required|string',
        'processed_at' => 'required|date',
        'revenue' => 'required|numeric',
        'tax' => 'required|numeric',
        'commission' => 'required|numeric',
        'merchant_commission' => 'required|numeric',
        'rolling_reserve' => 'required|numeric',
        'convert_rate_usd' => 'required|numeric',
        'collected_currency' => 'required|string',
        'payment_system' => 'required|string',
        'payment_system_subaccount' => 'present', //todo
    ];

    /**
     * @var int
     */
    protected int $id = self::ID;

    /**
     * @var string
     */
    protected string $name = 'Paymentwall';

    /**
     * @var string
     */
    protected string $systemMame = self::SYSTEM_NAME;

    /**
     * @param array $parameters
     *
     * @return bool
     * @throws Exception
     */
    public function webhook(array $parameters): bool
    {
        try {
            $this->validateWebhookData($parameters);

            $processorProject = ProcessorProject::findPWByExternalId($parameters['project_id']);

            if (empty($processorProject)) {
                throw_error(ErrorException::PROJECT_NOT_SUPPORTED);
            }

            ProcessorWebhook::add($processorProject->getId(), $parameters['reference'], $parameters);

            if ($parameters['type'] == 'payment') {
                event(new PaymentEvent($processorProject, $parameters));
            } else {
                throw_error(ErrorException::WEBHOOK_TYPE_NOT_SUPPORTED);
            }
        } catch (ValidationException $e) {
            Log::info(
                'Processor::webhook ValidationException',
                $e->errors()
            );

            throw $e;
        } catch (ErrorException $e) {
            Log::warning(
                'Processor::webhook ErrorException',
                ['code' => $e->getCode(), 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]
            );

            throw $e;
        }/* catch (Exception $e) {
            Log::error(
                'Processor::webhook Exception',
                ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]
            );

            throw $e;
        }*/

        return true;
    }


    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function createTransaction(array $data): ProcessorTransaction
    {
        return new PaymentwallTransaction(
            $this,
            $data['type'],
            $data['reference'],
            $data['project_id'],
            Arr::only($data, ['payment_system', 'payment_system_subaccount']),
            $data['country'],
            $data['currency'], //processing currency
            $data['convert_rate_usd'],
            $data['collected_currency'],
            $data['revenue'],
            $data['tax'],
            $data['commission'],
            $data['rolling_reserve'],
            $data['merchant_commission'],
            $data['processed_at'],
            $data['refund_amount'] ?? 0,
            $data['refund_currency_code'] ?? ''
        );
    }
}

