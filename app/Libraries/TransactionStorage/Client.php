<?php

namespace App\Libraries\TransactionStorage;

use App\Support\Guzzle\Manager;
use GuzzleHttp\Client as GuzzleClient;

class Client extends Manager
{
    /**
     * Client constructor.
     */
    public function __construct()
    {
        $transactionStorageConfig = config('services.transaction_storage');
        $guzzleConfig = [
            'base_uri' => trim($transactionStorageConfig['endpoint'], '/') . '/',
            'timeout' => 20,
            'handler' => $this->getHandlerStack(),
            'http_errors' => false,
            'headers' => [
                'X-SERVICE-ID' => $transactionStorageConfig['service_id'],
                'X-SECRET-KEY' => $transactionStorageConfig['secret_key'],
            ]
        ];

        $this->setHttpClient(new GuzzleClient($guzzleConfig));
    }

    /**
     * Send a post request to transaction api endpoint.
     *
     * @param $data
     *
     * @return mixed
     */
    public function postTransactions($data)
    {
        $response = $this->post(
            'transaction',
            [
                'form_params' => $data,
            ]
        );

        return $response->getBody();
    }
}
