<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Core;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use XNXK\LaravelEsign\Exceptions\HttpException;
use XNXK\LaravelEsign\Support\Collection;
use XNXK\LaravelEsign\Support\Log;

abstract class AbstractAPI
{
    public const JSON = 'json';
    public const SUCCESS_STATUS = 200;
    /**
     * Http instance.
     *
     * @var Http
     */
    protected $http;

    /**
     * The request token.
     *
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * @var int
     */
    protected static $maxRetries = 2;

    /**
     * Constructor.
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->setAccessToken($accessToken);
    }

    /**
     * Return the http instance.
     */
    public function getHttp(): Http
    {
        if (is_null($this->http)) {
            $this->http = new Http();
        }

        if (count($this->http->getMiddlewares()) === 0) {
            $this->registerHttpMiddlewares();
        }

        return $this->http;
    }

    /**
     * Set the http instance.
     *
     * @return $this
     */
    public function setHttp(Http $http)
    {
        $this->http = $http;

        return $this;
    }

    /**
     * Return the current accessToken.
     */
    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }

    /**
     * Set the request token.
     *
     * @return $this
     */
    public function setAccessToken(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public static function maxRetries(int $retries): void
    {
        self::$maxRetries = abs($retries);
    }

    /**
     * Parse JSON from response and check error.
     *
     * @param $method
     * @param array $args
     *
     * @throws HttpException
     */
    public function parseJSON($method, array $args): ?Collection
    {
        $http = $this->getHttp();

        $contents = $http->parseJSON(call_user_func_array([$http, $method], $args));

        if (empty($contents)) {
            return null;
        }

        $this->checkAndThrow($contents);

        return (new Collection($contents))->get('data');
    }

    /**
     * Put upload File.
     *
     * @param array $headers
     */
    public function httpPut(string $uploadUrls, string $fileContent, array $headers): mixed
    {
        $http = $this->getHttp();

        $status = $http->sendHttpPut($uploadUrls, $fileContent, $headers);

        if ($status !== self::SUCCESS_STATUS) {
            Log::debug('Request Upload File headers:' . json_encode($headers));
            Log::debug('Request Upload File url:' . $uploadUrls);
            throw new HttpException('文件上传失败！', 10001);
        }

        return $status;
    }

    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares(): void
    {
        // log
        $this->http->addMiddleware($this->logMiddleware());
        // retry
        $this->http->addMiddleware($this->retryMiddleware());
        // access token
        $this->http->addMiddleware($this->accessTokenMiddleware());
    }

    /**
     * Attache access token to request query.
     */
    protected function accessTokenMiddleware(): \Closure
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if (!$this->accessToken) {
                    return $handler($request, $options);
                }

                $request = $request->withHeader('X-Tsign-Open-App-Id', $this->accessToken->getAppId());
                $request = $request->withHeader('X-Tsign-Open-Token', $this->accessToken->getToken());
                $request = $request->withHeader('Content-Type', 'application/json');

                return $handler($request, $options);
            };
        };
    }

    /**
     * Log the request.
     */
    protected function logMiddleware(): \Closure
    {
        return Middleware::tap(static function (RequestInterface $request, $options): void {
            Log::debug("Request: {$request->getMethod()} {$request->getUri()} " . json_encode($options));
            Log::debug('Request headers:' . json_encode($request->getHeaders()));
        });
    }

    /**
     * Return retry middleware.
     */
    protected function retryMiddleware(): \Closure
    {
        return Middleware::retry(function (
            $retries,
            RequestInterface $request,
            ?ResponseInterface $response = null
        ) {
            // Limit the number of retries to 2
            if ($retries <= self::$maxRetries && $response && $body = $response->getBody()) {
                // Retry on server errors
                if (stripos($body, 'code') !== false && (stripos($body, '40001') !== false || stripos($body, '42001') !== false)) {
                    $token = $this->accessToken->getToken(true);

                    $request = $request->withHeader('X-Tsign-Open-App-Id', $this->accessToken->getAppId());
                    $request = $request->withHeader('X-Tsign-Open-Token', $token);
                    $request = $request->withHeader('Content-Type', 'application/json');

                    Log::debug("Retry with Request Token: {$token}");

                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Check the array data errors, and Throw exception when the contents contains error.
     *
     * @throws HttpException
     */
    protected function checkAndThrow(array $contents): void
    {
        if (isset($contents['code']) && $contents['code'] !== 0) {
            if (isset($contents['data'])) {
                Log::debug(json_encode($contents['data']));
            } else {
                if (empty($contents['message'])) {
                    $contents['message'] = 'Unknown';
                }

                throw new HttpException($contents['message'], $contents['code']);
            }
        }
    }
}
