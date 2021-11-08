<?php

namespace App\Support\Guzzle;

use GuzzleHttp\json_decode;
use Illuminate\Contracts\Support\Arrayable;
use Psr\Http\Message\ResponseInterface;

class Response implements Arrayable
{
    /**
     * @var \GuzzleHttp\Psr7\Response
     */
    protected $response;

    /**
     * Create new response instance.
     *
     * @param ResponseInterface $response
     *
     * @return void
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Check whether request is success.
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->status() >= 200 && $this->status() < 300;
    }

    /**
     * Check whether request is redirect.
     *
     * @return bool
     */
    public function isRedirect()
    {
        return $this->status() >= 300 && $this->status() < 400;
    }

    /**
     * Check whether request is client error.
     *
     * @return bool
     */
    public function isClientError()
    {
        return $this->status() >= 400 && $this->status() < 500;
    }

    /**
     * Check whether request is server error.
     *
     * @return bool
     */
    public function isServerError()
    {
        return $this->status() >= 500;
    }

    /**
     * Get http response status code.
     *
     * @return int
     */
    public function status()
    {
        return $this->response->getStatusCode();
    }

    /**
     * Get response body.
     *
     * @return string
     */
    public function body()
    {
        return (string)$this->response->getBody();
    }

    /**
     * Get the json response as array.
     *
     * @param bool $assoc
     * @param int  $depth
     * @param int  $options
     *
     * @return mixed
     */
    public function json($assoc = true, $depth = 512, $options = 0)
    {
        return json_decode($this->body(), $assoc, $depth, $options);
    }

    /**
     * Get the json response as array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->json();
    }

    /**
     * Get an attribute from the json.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return data_get($this->json(), $key, $default);
    }

    /**
     * Dynamically retrieve the value of json.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Dynamically call the response instance.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->response->{$method}(...$parameters);
    }
}
