<?php

namespace App\Libraries\Ledger;

use App\Support\Guzzle\Manager;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;

class Client extends Manager
{
    /**
     * Client constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $guzzleConfig = [
            'base_uri' => trim(config('services.ledger.endpoint' . '_' . $config['ledger_endpoint_key']), '/') . '/',
            'timeout' => 20,
            'handler' => $this->getHandlerStack(),
            'http_errors' => false,
        ];

        $this->setHttpClient(new GuzzleClient($guzzleConfig));
    }

   hidden
}
