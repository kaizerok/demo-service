<?php

namespace App\Processors;

use App\Exceptions\ErrorException;
use App\Models\PaymentSystem;
use App\Models\ProcessorPaymentSystem;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class ProcessorAbstract
{
    const WEBHOOK_PARAMETERS = [];

    /**
     * @var int
     */
    protected int $id;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $systemMame;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSystemName(): string
    {
        return $this->systemMame;
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws Exception
     */
    public function validateWebhookData(array $data): bool
    {
        $validator = Validator::make($data, static::WEBHOOK_PARAMETERS);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

    /**
     * @param array $data
     *
     * @return PaymentSystem
     * @throws ErrorException
     */
    public static function getPaymentSystem(array $data): PaymentSystem
    {
        $paymentSystemCode = empty($data['payment_system_subaccount'])
            ? $data['payment_system']
            : $data['payment_system_subaccount'];

        $processorPaymentSystem = ProcessorPaymentSystem::findPWByCode($paymentSystemCode);

        if (!$processorPaymentSystem) {
            throw_error(ErrorException::PAYMENT_SYSTEM_NOT_SUPPORTED);
        }

        return $processorPaymentSystem->paymentSystem;
    }
}
