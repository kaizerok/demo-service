<?php

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

if (!function_exists('log_event')) {
    /**
     * Log event data with title = event name:request id.
     *
     * @param string $event
     * @param mixed  $context
     * @param string $level
     *
     * @return void
     */
    function log_event($event, $context = [], $level = 'info')
    {
        $title = $event . ':' . session('request_id');

        if ($context instanceof Arrayable) {
            $context = $context->toArray();
        } elseif ($context instanceof Jsonable) {
            $context = json_decode($context->toJson(), true);
        } elseif ($context instanceof JsonSerializable) {
            $context = $context->jsonSerialize();
        } else {
            $context = (array)$context;
        }

        $context['@timestamp'] = floor(microtime(true) * 1000);
        $context['@event'] = $event;

        logger()->channel('event')->{$level}($title, $context);
    }
}

if (!function_exists('custom_round')) {
    /**
     * @param     $value
     * @param int $precision
     *
     * @return float
     */
    function custom_round($value, $precision = 4)
    {
        return round($value, $precision);
    }
}

if (! function_exists('throw_error')) {
    /**
     * Throw ErrorException.
     *
     * @param int            $code
     * @param int|null       $statusCode
     * @param Exception|null $previous
     *
     * @throws \App\Exceptions\ErrorException
     */
    function throw_error($code, $statusCode = null, Exception $previous = null)
    {
        throw new \App\Exceptions\ErrorException($code, $statusCode, $previous);
    }
}
