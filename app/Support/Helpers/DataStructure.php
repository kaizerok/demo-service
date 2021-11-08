<?php

namespace App\Support\Helpers;

/**
 * Class DataStructure
 *
 * @package App\Support\Helpers
 */
abstract class DataStructure
{
    const STATUS_PAID = 1;
    const STATUS_CHARGEBACK = 2;
    const STATUS_REFUND = 3;
    const STATUS_PARTIAL_REFUND = 4;

    protected function morphParams()
    {
    }
}
