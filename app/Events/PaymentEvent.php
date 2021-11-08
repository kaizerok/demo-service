<?php

namespace App\Events;

use App\Models\ProcessorProject;
use Illuminate\Queue\SerializesModels;

/**
 * Class PaymentEvent
 *
 * @package App\Events
 */
class PaymentEvent
{
    use SerializesModels;

    public ProcessorProject $processorProject;
    public array $data;

    public function __construct(
        ProcessorProject $processorProject,
        array $parameters
    ) {
        $this->processorProject = $processorProject;
        $this->data = $parameters;
    }
}
