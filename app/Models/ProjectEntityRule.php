<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEntityRule extends Model
{
    use HasFactory;

    const ALL_COUNTRIES_ID = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * @return int
     */
    public function getProcessingEntityId()
    {
        return $this->processing_entity_id;
    }

    /**
     * @return int
     */
    public function getTaxEntityId()
    {
        return $this->tax_entity_id;
    }

    /**
     * @return int
     */
    public function getPayoutEntityId()
    {
        return $this->payout_entity_id;
    }

    /**
     * @param int $paymentSystemId
     *
     * @return PaymentSystem[]
     */
    public static function getListByPaymentSystemId(int $paymentSystemId)
    {
        return self::where('payment_system_id', $paymentSystemId)->get();
    }
}
