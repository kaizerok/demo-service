<?php

namespace App\Support\Guzzle;

use Closure;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * A class to log HTTP Requests and Responses of Guzzle.
 */
class LogMiddleware
{
    /**
     * @var bool whether or not to log requests as they are made
     */
    private $onExceptionOnly;

    /**
     * @var bool
     */
    private $logStatistics;

    /**
     * @var array
     */
    private $thresholds;

    /**
     * @var array
     */
    private $logCodeLevel = [];

    /**
     * @var bool
     */
    private $sensitive;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Creates a callable middleware for logging requests and responses.
     *
     * @param LoggerInterface $logger
     * @param bool            $onExceptionOnly the request and the response will be logged only in cases there is an exception or if they status code exceeds the thresholds
     * @param bool            $logStatistics   if this is true an extra row will be added that will contain some HTTP statistics
     * @param array           $thresholds
     */
    public function __construct(
        LoggerInterface $logger,
        $onExceptionOnly = false,
        $logStatistics = true,
        array $thresholds = []
    ) {
        $this->logger = $logger;
        $this->onExceptionOnly = $onExceptionOnly;
        $this->logStatistics = $logStatistics;
        $this->thresholds = array_merge([
            'error' => 499,
            'warning' => 399,
        ], $thresholds);
    }

    /**
     * Called when the middleware is handled by the client.
     *
     * @param callable $handler
     *
     * @return Closure
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $this->setOptions($options);

            if ($this->onExceptionOnly === false) {
                $this->logRequest($request);
                if ($this->logStatistics && !isset($options['on_stats'])) {
                    $options['on_stats'] = $this->logStatistics();
                }
            }

            return $handler($request, $options)
                ->then(
                    $this->handleSuccess($request),
                    $this->handleFailure($request)
                );
        };
    }

    /**
     * Returns the default log level for a response.
     *
     * @param RequestInterface|ResponseInterface|\Exception $message
     *
     * @return string LogLevel
     */
    protected function getLogLevel($message = null)
    {
        if ($message === null || ($message instanceof \Exception)) {
            return LogLevel::CRITICAL;
        }

        if ($message instanceof RequestInterface) {
            return LogLevel::DEBUG;
        }

        if ($message instanceof ResponseInterface) {
            $code = $message->getStatusCode();
            if ($code === 0) {
                return LogLevel::CRITICAL;
            }

            if (isset($this->logCodeLevel[$code])) {
                return $this->logCodeLevel[$code];
            }

            if ($this->thresholds['error'] !== null && $code > $this->thresholds['error']) {
                return LogLevel::CRITICAL;
            }

            if ($this->thresholds['warning'] !== null && $code > $this->thresholds['warning']) {
                return LogLevel::ERROR;
            }

            return LogLevel::DEBUG;
        }

        throw new \InvalidArgumentException('Could not retrieve the log level because of unknown message class.');
    }

    /**
     * @param RequestInterface $request
     *
     * @return void
     */
    protected function logRequest(RequestInterface $request)
    {
        $this->logger->log(
            $this->getLogLevel($request),
            'Guzzle HTTP request',
            $this->withRequestContext($request)
        );
    }

    /**
     * @return Closure
     */
    protected function logStatistics()
    {
        return function (TransferStats $stats) {
            $this->logger->debug('Guzzle HTTP statistics', [
                'time' => $stats->getTransferTime(),
                'uri' => $stats->getEffectiveUri(),
            ]);
        };
    }

    /**
     * @param ResponseInterface $response
     *
     * @return void
     */
    protected function logResponse(ResponseInterface $response)
    {
        $this->logger->log(
            $this->getLogLevel($response),
            'Guzzle HTTP response',
            $this->withResponseContext($response)
        );
    }

    /**
     * Returns a function which is handled when a request was successful.
     *
     * @param RequestInterface $request
     *
     * @return Closure
     */
    protected function handleSuccess(RequestInterface $request)
    {
        return function (ResponseInterface $response) use ($request) {
            if ($this->onExceptionOnly === false) {
                $this->logResponse($response);
                return $response;
            }

            if ($response->getStatusCode() > $this->thresholds['warning']) {
                $this->logRequest($request);
                $this->logResponse($response);
            }
            return $response;
        };
    }

    /**
     * Returns a function which is handled when a request was rejected.
     *
     * @param RequestInterface $request
     *
     * @return Closure
     */
    protected function handleFailure(RequestInterface $request)
    {
        return function (\Exception $reason) use ($request) {
            if ($this->onExceptionOnly === true) {
                $this->logRequest($request);
            }

            if ($reason instanceof RequestException && $reason->hasResponse()) {
                $this->logResponse($reason->getResponse());
                return \GuzzleHttp\Promise\rejection_for($reason);
            }

            $this->logger->log($this->getLogLevel($reason), 'Guzzle HTTP exception', $this->withReasonContext($reason));
            return \GuzzleHttp\Promise\rejection_for($reason);
        };
    }

    /**
     * Merges and return the response context.
     *
     * @param \Exception $reason
     * @param array      $context
     *
     * @return array
     */
    protected function withReasonContext(\Exception $reason, array $context = [])
    {
        $context['reason']['code'] = $reason->getCode();
        $context['reason']['message'] = $reason->getMessage();
        return $context;
    }

    /**
     * Merges and return the request context.
     *
     * @param RequestInterface $request
     * @param array            $context
     *
     * @return array
     */
    protected function withRequestContext(RequestInterface $request, array $context = [])
    {
        $context['request']['method'] = $request->getMethod();
        $context['request']['headers'] = $request->getHeaders();
        $context['request']['uri'] = $request->getRequestTarget();
        $context['request']['version'] = 'HTTP/' . $request->getProtocolVersion();

        if ($request->getBody()->getSize() !== 0) {
            $context['request']['body'] = $this->getBody($request);
        }

        return $context;
    }

    /**
     * Merges and return the response context.
     *
     * @param ResponseInterface $response
     * @param array             $context
     *
     * @return array
     */
    protected function withResponseContext(ResponseInterface $response, array $context = [])
    {
        $context['response']['headers'] = $response->getHeaders();
        $context['response']['statusCode'] = $response->getStatusCode();
        $context['response']['version'] = 'HTTP/' . $response->getProtocolVersion();
        $context['response']['message'] = $response->getReasonPhrase();

        if ($response->getBody()->getSize() !== 0) {
            $context['response']['body'] = $this->getBody($response);
        }

        return $context;
    }

    /**
     * @param MessageInterface $message
     *
     * @return string
     */
    protected function getBody(MessageInterface $message)
    {
        $stream = $message->getBody();
        if ($stream->isSeekable() === false || $stream->isReadable() === false) {
            return 'Body stream is not seekable/readable.';
        }

        if ($this->sensitive === true) {
            return 'Body contains sensitive information therefore it is not included.';
        }

        if ($stream->getSize() >= 3500) {
            return $stream->read(200) . ' (truncated...)';
        }

        $body = $stream->getContents();
        $isJson = preg_grep('/application\/[\w\.\+]*(json)/', $message->getHeader('Content-Type'));
        if (!empty($isJson)) {
            $body = json_decode($body, true);
        }

        $stream->rewind();
        return $body;
    }

    /**
     * @param array $options
     *
     * @return void
     */
    protected function setOptions(array $options)
    {
        if (!isset($options['log'])) {
            return;
        }

        $options = $options['log'];
        if (isset($options['requests'])) {
            @trigger_error('Using option "requests" is deprecated and it will be removed on the next version. Use "on_exception_only"',
                E_USER_DEPRECATED);
            if (!isset($options['on_exception_only'])) {
                $options['on_exception_only'] = !$options['requests'];
            }
        }

        $defaults = [
            'on_exception_only' => $this->onExceptionOnly,
            'statistics' => $this->logStatistics,
            'warning_threshold' => 399,
            'error_threshold' => 499,
            'levels' => [],
            'sensitive' => false,
        ];

        $options = array_merge($defaults, $options);
        $this->logCodeLevel = $options['levels'];
        $this->thresholds['warning'] = $options['warning_threshold'];
        $this->thresholds['error'] = $options['error_threshold'];
        $this->onExceptionOnly = $options['on_exception_only'];
        $this->logStatistics = $options['statistics'];
        $this->sensitive = $options['sensitive'];
    }
}
