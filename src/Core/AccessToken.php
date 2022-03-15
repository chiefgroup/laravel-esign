<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Core;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use XNXK\LaravelEsign\Exceptions\HttpException;

class AccessToken
{
    public const API_TOKEN_GET = '/v1/oauth2/access_token';
    protected $appId;

    protected $secret;

    protected $cache;

    protected $cacheKey;

    protected $http;

    protected $tokenJsonKey = 'token';

    protected $prefix = 'esign.common.access_token.';

    public function __construct($appId, $secret, ?Cache $cache = null)
    {
        $this->appId = $appId;
        $this->secret = $secret;
        $this->cache = $cache;
    }

    /**
     * @throws HttpException
     */
    public function getToken(bool $forceRefresh = false): mixed
    {
        $cacheKey = $this->getCacheKey();
        $cached = $this->getCache()->fetch($cacheKey);

        if ($forceRefresh || empty($cached)) {
            $token = $this->getTokenFromServer();
            $this->getCache()->save($cacheKey, $token['data'][$this->tokenJsonKey], 60 * 5);

            return $token['data'][$this->tokenJsonKey];
        }

        return $cached;
    }

    /**
     * @throws HttpException
     */
    public function getTokenFromServer(): mixed
    {
        $params = [
            'appId' => $this->appId,
            'secret' => $this->secret,
            'grantType' => 'client_credentials',
        ];

        $http = $this->getHttp();

        $token = $http->parseJSON($http->get(self::API_TOKEN_GET, $params));

        if (empty($token['data'][$this->tokenJsonKey])) {
            throw new HttpException('Request AccessToken fail. response: ' . json_encode($token, JSON_UNESCAPED_UNICODE));
        }

        return $token;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function getHttp()
    {
        return $this->http ?: $this->http = new Http();
    }

    public function setHttp($http)
    {
        $this->http = $http;

        return $this;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function setCacheKey($cacheKey)
    {
        $this->cacheKey = $cacheKey;

        return $this;
    }

    protected function getCache()
    {
        return $this->cache ?: $this->cache = new FilesystemCache(sys_get_temp_dir());
    }

    protected function getCacheKey()
    {
        if (is_null($this->cacheKey)) {
            return $this->prefix . $this->appId;
        }

        return $this->cacheKey;
    }
}
