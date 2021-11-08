<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * This class should be used to handle all pre-defined errors such as
 * error response from payment services/partners with their error code
 * and explanation. We must define the list of the error code and it's
 * response.
 *
 * For other use cases, throws HttpException or use abort() helper.
 */
class ErrorException extends HttpException
{
    /**
     * The error code.
     *
     * @var int
     */
    const PROJECT_NOT_SUPPORTED                         = 8000;
    const WEBHOOK_TYPE_NOT_SUPPORTED                    = 7000;

    const PAYMENT_SYSTEM_NOT_SUPPORTED                  = 9000;
    const PAYMENT_SYSTEM_NOT_ENABLED                    = 9001;
    const PAYMENT_SYSTEM_NOT_AVAILABLE                  = 9002;
    const PAYMENT_SYSTEM_INVALID_CREDENTIAL             = 9003;
    const PAYMENT_SYSTEM_ONBOARDING_API_ERROR           = 9004;
    const PAYMENT_SYSTEM_ONBOARDING_API_NOT_SUPPORTED   = 9005;
    const PAYMENT_SYSTEM_MANUAL_CONVERT_NOT_SUPPORTED   = 9006;

    const PAYMENT_NOT_FOUND                             = 3000;
    const PAYMENT_UNABLE_TO_PROCESS                     = 3100;
    const PAYMENT_UNABLE_TO_PROCESS_CREDENTIAL          = 3101;
    const PAYMENT_UNABLE_TO_PROCESS_AMOUNT              = 3102;
    const PAYMENT_UNABLE_TO_PROCESS_CURRENCY            = 3103;
    const PAYMENT_UNABLE_TO_PROCESS_BLACKLIST           = 3104;
    const PAYMENT_UNABLE_TO_PROCESS_STATUS_COMPLETED    = 3120;
    const PAYMENT_UNABLE_TO_PROCESS_STATUS_REJECTED     = 3121;
    const PAYMENT_ACTION_NOT_SUPPORTED                  = 3200;
    const PAYMENT_SUBSCRIPTION_NOT_SUPPORTED            = 3300;
    const PAYMENT_MANUAL_CONVERT_UNABLE_TO_PROCESS      = 3400;

    const PROCESSING_REFUND_UNABLE_TO_PROCESS           = 4000;
    const PROCESSING_REFUND_NON_REFUNDABLE              = 4001;
    const PROCESSING_REFUND_NON_PARTIAL_REFUNDABLE      = 4002;
    const PROCESSING_REFUND_ALREADY_REFUNDED            = 4003;
    const PROCESSING_REFUND_ALREADY_PENDING             = 4004;
    const PROCESSING_REFUND_FULL_AFTER_PARTIAL_REFUND   = 4005;

    const FEE_STORAGE_ERROR_PROCESSING                  = 5000;
    const FEE_STORAGE_EMPTY_RESULT                      = 5100;

    const SUBSCRIPTION_NOT_FOUND                        = 6000;
    const SUBSCRIPTION_NOT_ACTIVATED                       = 6001;
    const SUBSCRIPTION_CHARGE_EXPIRED                       = 6002;

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    |
    | Here you may define all error messages along with their status code
    | and headers for the application. The message could be either a string
    |
    | self::ERROR_EXAMPLE => 'This is the example message'
    |
    | or an array with the following format, statusCode and headers are
    | optional
    |
    | self::ERROR_EXAMPLE => [
    |     'message' => 'This is the example message',
    |     'statusCode' => 400,
    |     'headers' => [
    |         'X-Header-One' => 1,
    |         'X-Header-Two' => 2,
    |     ],
    | ],
    |
    */
    public static array $messages = [
        self::PROJECT_NOT_SUPPORTED => [
            'message' => 'Project you are trying to access does not exist',
            'statusCode' => 400,
        ],
        self::WEBHOOK_TYPE_NOT_SUPPORTED => [
            'message' => 'Webhook type is not supported',
            'statusCode' => 400,
        ],

        /**
         * SP ones
         */
        self::PAYMENT_SYSTEM_NOT_SUPPORTED => [
            'message' => 'The payment system is not supported.',
            'statusCode' => 400,
        ],
        self::PAYMENT_SYSTEM_NOT_ENABLED => [
            'message' => 'The payment system is not enabled for your merchant.',
            'statusCode' => 403,
        ],
        self::PAYMENT_SYSTEM_NOT_AVAILABLE => [
            'message' => 'The payment system is not available for your merchant.',
            'statusCode' => 403,
        ],
        self::PAYMENT_SYSTEM_INVALID_CREDENTIAL => [
            'message' => 'The payment system credential is missing or invalid.',
            'statusCode' => 400,
        ],
        self::PAYMENT_SYSTEM_ONBOARDING_API_ERROR => [
            'message' => 'Merchant onboarding API error.',
            'statusCode' => 500,
        ],
        self::PAYMENT_SYSTEM_ONBOARDING_API_NOT_SUPPORTED => [
            'message' => 'Merchant onboarding API is not supported for this payment system.',
            'statusCode' => 400,
        ],
        self::PAYMENT_NOT_FOUND => [
            'message' => 'Payment was not found.',
            'statusCode' => 404,
        ],
        self::SUBSCRIPTION_NOT_FOUND => [
            'message' => 'Subscription was not found.',
            'statusCode' => 404,
        ],

        self::SUBSCRIPTION_NOT_ACTIVATED => [
            'message' => 'Subscription was not activate.',
            'statusCode' => 400,
        ],
        self::SUBSCRIPTION_CHARGE_EXPIRED => [
            'message' => 'The subscription for the current period has been paid.',
            'statusCode' => 400,
        ],

        self::PAYMENT_UNABLE_TO_PROCESS => [
            'message' => 'Unable to process payment.',
            'statusCode' => 400,
        ],
        self::PAYMENT_UNABLE_TO_PROCESS_CREDENTIAL => [
            'message' => 'Unable to process payment.',
            'statusCode' => 400,
        ],
        self::PAYMENT_UNABLE_TO_PROCESS_AMOUNT => [
            'message' => 'Unable to process payment because the amount is invalid.',
            'statusCode' => 400,
        ],
        self::PAYMENT_UNABLE_TO_PROCESS_CURRENCY => [
            'message' => 'Unable to process payment because the currency is not supported.',
            'statusCode' => 400,
        ],
        self::PAYMENT_UNABLE_TO_PROCESS_BLACKLIST => [
            'message' => 'Unable to process payment because the payment has been blacklisted by risk engine.',
            'statusCode' => 403,
        ],
        self::PAYMENT_UNABLE_TO_PROCESS_STATUS_COMPLETED => [
            'message' => 'Unable to process payment because it had been completed.',
            'statusCode' => 400,
        ],
        self::PAYMENT_UNABLE_TO_PROCESS_STATUS_REJECTED => [
            'message' => 'Unable to process payment because it had been rejected.',
            'statusCode' => 400,
        ],
        self::PAYMENT_ACTION_NOT_SUPPORTED => [
            'message' => 'This action is not supported.',
            'statusCode' => 400,
        ],
        self::PAYMENT_SUBSCRIPTION_NOT_SUPPORTED => [
            'message' => 'The subscription is not supported.',
            'statusCode' => 400,
        ],

        self::PROCESSING_REFUND_UNABLE_TO_PROCESS => [
            'message' => 'Unable to process refund request.',
            'statusCode' => 400,
        ],
        self::PROCESSING_REFUND_NON_REFUNDABLE => [
            'message' => 'Payment is non refundable.',
            'statusCode' => 403,
        ],
        self::PROCESSING_REFUND_NON_PARTIAL_REFUNDABLE => [
            'message' => 'Partial refund is not supported.',
            'statusCode' => 403,
        ],
        self::PROCESSING_REFUND_ALREADY_REFUNDED => [
            'message' => 'Payment is already refunded.',
            'statusCode' => 403,
        ],
        self::PROCESSING_REFUND_ALREADY_PENDING => [
            'message' => 'There is already a pending refund request for this payment.',
            'statusCode' => 403,
        ],
        self::PROCESSING_REFUND_FULL_AFTER_PARTIAL_REFUND => [
            'message' => 'Full refund is not allowed after partial refund.',
            'statusCode' => 403,
        ],

        self::FEE_STORAGE_ERROR_PROCESSING => [
            'message' => 'Fees storage error.',
            'statusCode' => 500,
        ],
        self::FEE_STORAGE_EMPTY_RESULT => [
            'message' => 'There are no fee that match the condition.',
            'statusCode' => 400,
        ],
    ];

    public function __construct($code = 0, $status = null, \Exception $previous = null)
    {
        $error = data_get(static::$messages, $code);
        $message = data_get($error, 'message');
        $statusCode = $status ?: data_get($error, 'statusCode', 500);
        $headers = data_get($error, 'headers', []);

        parent::__construct($statusCode, $message, $previous, $headers, intval($code));
    }
}
