<?php

namespace App\Processors;

use Exception;
use Illuminate\Validation\ValidationException;

/**
 * Interface Processor provides carcass to manage third party services
 * that provides payment processor services
 *
 * @package App\Processors
 */
interface Processor
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getSystemName(): string;

    /**
     * @param array $parameters
     *
     * @return bool
     * @throws Exception
     */
    public function webhook(array $parameters): bool;

    /**
     * Every callback from payment processor should be validated proper way
     *
     * @param array $data
     *
     * @return bool
     * @throws ValidationException
     */
    public function validateWebhookData(array $data): bool;

    /**
     * Creates standardized transaction from processor callback
     *
     * @param array $data
     *
     * @return ProcessorTransaction
     */
    public function createTransaction(array $data): ProcessorTransaction;
}
