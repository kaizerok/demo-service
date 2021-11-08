<?php

namespace App\Support\Guzzle;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;

class Manager
{
    /**
     * @var ClientInterface
     */
    protected ClientInterface $client;

    /**
     * Set http client instance.
     *
     * @param ClientInterface $client
     *
     * @return $this
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get http client instance.
     *
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        return $this->client;
    }

    /**
     * Get handle stack.
     *
     * @param callable|null $handler
     *
     * @return HandlerStack
     */
    protected function getHandlerStack(callable $handler = null)
    {
        $stack = HandlerStack::create($handler);
        $stack->push(new LogMiddleware(logs('guzzle')));

        return $stack;
    }

    /**
     * Create response handler.
     *
     * @param ResponseInterface $response
     *
     * @return Response
     */
    protected function createResponseHandler(ResponseInterface $response)
    {
        return new Response($response);
    }

    /**
     * Dynamically call the client instance.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $response = $this->getHttpClient()->{$method}(...$parameters);

        if ($response instanceof ResponseInterface) {
            return $this->createResponseHandler($response);
        }

        return $response;
    }
}
