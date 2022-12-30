<?php

/*
 * This file is part of the chiefgroup/laravel-esign.
 *
 * (c) peng <2512422541@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace QF\LaravelEsign\Core;

use GuzzleHttp\Middleware;
use QF\LaravelEsign\Exceptions\HttpException;
use QF\LaravelEsign\Support\Collection;
use QF\LaravelEsign\Support\Log;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractAPI
{
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

    public const JSON = 'json';
    public const SUCCESS_STATUS = 200;

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
     *
     * @return Http
     */
    public function getHttp()
    {
        if (is_null($this->http)) {
            $this->http = new Http();
        }

        if (0 === count($this->http->getMiddlewares())) {
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
     *
     * @return AccessToken
     */
    public function getAccessToken()
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

    /**
     * @param int $retries
     */
    public static function maxRetries($retries)
    {
        self::$maxRetries = abs($retries);
    }

    /**
     * Parse JSON from response and check error.
     *
     * @param $method
     * @param array $args
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function parseJSON($method, array $args)
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
     * Put upload File
     * @param string $uploadUrls
     * @param string $fileContent
     * @param array $headers
     * @return int|mixed
     */
    public function httpPut($uploadUrls, $fileContent, $headers)
    {
        $http = $this->getHttp();

        $status = $http->sendHttpPut($uploadUrls, $fileContent, $headers);

        if ($status != self::SUCCESS_STATUS) {
            Log::debug('Request Upload File headers:'.json_encode($headers));
            Log::debug('Request Upload File url:'.$uploadUrls);
            throw new HttpException('文件上传失败！', 10001);
        }

        return $status;
    }

    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares()
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
     *
     * @return \Closure
     */
    protected function accessTokenMiddleware()
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
     *
     * @return \Closure
     */
    protected function logMiddleware()
    {
        return Middleware::tap(function (RequestInterface $request, $options) {
            Log::debug("Request: {$request->getMethod()} {$request->getUri()} ".json_encode($options));
            Log::debug('Request headers:'.json_encode($request->getHeaders()));
        });
    }

    /**
     * Return retry middleware.
     *
     * @return \Closure
     */
    protected function retryMiddleware()
    {
        return Middleware::retry(function (
            $retries,
            RequestInterface $request,
            ResponseInterface $response = null
        ) {
            // Limit the number of retries to 2
            if ($retries <= self::$maxRetries && $response && $body = $response->getBody()) {
                // Retry on server errors
                if (false !== stripos($body, 'code') && (false !== stripos($body, '40001') || false !== stripos($body, '42001'))) {
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
    protected function checkAndThrow(array $contents)
    {
        if (isset($contents['code']) && 0 !== $contents['code']) {
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
