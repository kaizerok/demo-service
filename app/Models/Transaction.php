<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 *
 * @package App\Models
 */
class Transaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'extra_data' => 'array'
    ];

    /**
     * @param $type
     * @param $paymentSystemName
     * @param $reference
     * @param $merchantId
     * @param $supplier
     * @param $entity
     * @param $paymentAgent
     * @param $countryCode
     * @param $currencyCode
     * @param $collectedCurrencyCode
     * @param $amount
     * @param $income
     * @param $merchantRevenue
     * @param $tax
     * @param $provisionReserve
     * @param $commission
     * @param $rollingReserve
     * @param $convertedAmount
     * @param $convertIncome
     * @param $convertedMerchantRevenue
     * @param $convertedTax
     * @param $convertedProvisionReserve
     * @param $convertedCommission
     * @param $convertedRollingReserve
     * @param $convertedCurrency
     * @param $convertRate
     * @param $currencyConvertFee
     * @param $date
     * @param $expectedCollectingDate
     * @param $extraData
     *
     * @return static
     */
    public static function fillEntry(
        $type,
        $paymentSystemName,
        $reference,
        $merchantId,
        $supplier,
        $entity,
        $paymentAgent,
        $countryCode,
        $currencyCode,
        $collectedCurrencyCode,
        $amount,
        $income,
        $merchantRevenue,
        $tax,
        $provisionReserve,
        $commission,
        $rollingReserve,
        $convertedAmount,
        $convertIncome,
        $convertedMerchantRevenue,
        $convertedTax,
        $convertedProvisionReserve,
        $convertedCommission,
        $convertedRollingReserve,
        $convertedCurrency,
        $convertRate,
        $currencyConvertFee,
        $date,
        $expectedCollectingDate,
        $extraData
    ) {
        $model = new static();

        $model->setAttribute('type', $type);
        $model->setAttribute('payment_system_name', $paymentSystemName);
        $model->setAttribute('reference', $reference);
        $model->setAttribute('merchant_id', $merchantId);
        $model->setAttribute('supplier', $supplier);
        $model->setAttribute('entity', $entity);
        $model->setAttribute('payment_agent', $paymentAgent);
        $model->setAttribute('country_code', $countryCode);

        $model->setAttribute('currency_code', $currencyCode);
        $model->setAttribute('collected_currency_code', $collectedCurrencyCode);

        $model->setAttribute('amount', $amount);
        $model->setAttribute('income', $income);
        $model->setAttribute('merchant_revenue', $merchantRevenue);
        $model->setAttribute('tax', $tax);
        $model->setAttribute('provision_reserve', $provisionReserve);
        $model->setAttribute('commission', $commission);
        $model->setAttribute('rolling_reserve', $rollingReserve);

        $model->setAttribute('converted_amount', $convertedAmount);
        $model->setAttribute('converted_income', $convertIncome);
        $model->setAttribute('converted_merchant_revenue', $convertedMerchantRevenue);
        $model->setAttribute('converted_tax', $convertedTax);
        $model->setAttribute('converted_provision_reserve', $convertedProvisionReserve);
        $model->setAttribute('converted_commission', $convertedCommission);
        $model->setAttribute('converted_rolling_reserve', $convertedRollingReserve);

        $model->setAttribute('convert_currency_code', $convertedCurrency);
        $model->setAttribute('convert_rate', $convertRate);
        $model->setAttribute('currency_convert_fee', $currencyConvertFee);

        $model->setAttribute('date', $date);
        $model->setAttribute('expected_collecting_date', $expectedCollectingDate);

        $model->setAttribute('extra_data', $extraData);

        return $model;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getAttributeValue('type');
    }

    /**
     * @return string
     */
    public function getPaymentSystemName()
    {
        return $this->getAttributeValue('payment_system_name');
    }

    /**
     * @return float
     */
    public function getMerchantRevenue()
    {
        return $this->getAttributeValue('merchant_revenue');
    }

    /**
     * @return float
     */
    public function getConvertedMerchantRevenue()
    {
        return $this->getAttributeValue('converted_merchant_revenue');
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getAttributeValue('country_code');
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getAttributeValue('merchant_id');
    }

    /**
     * @return string
     */
    public function getSupplier()
    {
        return $this->getAttributeValue('supplier');
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->getAttributeValue('entity');
    }

    /**
     * @return string
     */
    public function getPaymentAgent()
    {
        return $this->getAttributeValue('payment_agent');
    }

    /**
     * @return float
     */
    public function getTax()
    {
        return $this->getAttributeValue('tax');
    }

    /**
     * @return float
     */
    public function getConvertedTax()
    {
        return $this->getAttributeValue('converted_tax');
    }

    /**
     * @return float
     */
    public function getProvisionReserve()
    {
        return $this->getAttributeValue('provision_reserve');
    }

    /**
     * @return float
     */
    public function getConvertedProvisionReserve()
    {
        return $this->getAttributeValue('converted_provision_reserve');
    }

    /**
     * @return float
     */
    public function getWithholdingTax()
    {
        return 0;
        return $this->getAttributeValue('withholding_tax');
    }

    /**
     * @return float
     */
    public function getConvertedWithholdingTax()
    {
        return 0;
        return $this->getAttributeValue('converted_withholding_tax');
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->getAttributeValue('reference');
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->getAttributeValue('amount');
    }

    /**
     * @return float
     */
    public function getConvertedAmount()
    {
        return $this->getAttributeValue('converted_amount');
    }

    /**
     * @return float
     */
    public function getIncome()
    {
        return $this->getAttributeValue('income');
    }

    /**
     * @return float
     */
    public function getConvertedIncome()
    {
        return $this->getAttributeValue('converted_income');
    }

    /**
     * @return float
     */
    public function getCommission()
    {
        return $this->getAttributeValue('commission');
    }

    /**
     * @return float
     */
    public function getConvertedCommission()
    {
        return $this->getAttributeValue('converted_commission');
    }

    /**
     * @return float
     */
    public function getConvertRate()
    {
        return $this->getAttributeValue('convert_rate');
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->getAttributeValue('currency_code');
    }

    /**
     * @return string
     */
    public function getCollectedCurrency()
    {
        return $this->getAttributeValue('collected_currency_code');
    }

    /**
     * @return string
     */
    public function getConvertCurrency()
    {
        return $this->getAttributeValue('convert_currency_code');
    }

    /**
     * @return float
     */
    public function getRollingReserve()
    {
        return $this->getAttributeValue('rolling_reserve');
    }

    /**
     * @return float
     */
    public function getConvertedRollingReserve()
    {
        return $this->getAttributeValue('converted_rolling_reserve');
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->getAttributeValue('date');
    }

    /**
     * @return string|null
     */
    public function getExpectedCollectingDate()
    {
        return $this->getAttributeValue('expected_collecting_date');
    }

    /**
     * @return array
     */
    public function getExtraData()
    {
        return $this->getAttributeValue('extra_data');
    }
}
