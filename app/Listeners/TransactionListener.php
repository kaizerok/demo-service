<?php

namespace App\Listeners;

use App\Support\Helpers\TransactionProcessor;
use Exception;
use App\Events\PaymentEvent;

/**
 * Class TransactionListener
 *
 * @package App\Listeners
 */
class TransactionListener
{
    /**
     * @param PaymentEvent $event
     *
     * @throws Exception
     */
    public function handle($event)
    {
        (new TransactionProcessor($event->processorProject, $event->data))
            ->process();
    }
}
