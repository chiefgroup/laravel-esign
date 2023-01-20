<?php

namespace QF\LaravelEsign\Auth;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\InvalidArgumentException;
use QF\LaravelEsign\Application;
use QF\LaravelEsign\Kernel\Exceptions\BadResponseException;
use QF\LaravelEsign\Kernel\Exceptions\HttpException;
use QF\LaravelEsign\Kernel\Exceptions\RuntimeException;
use QF\LaravelEsign\Kernel\Traits\HasHttpRequests;
use QF\LaravelEsign\Kernel\Traits\InteractsWithCache;
use QF\LaravelEsign\Kernel\Traits\ResponseCastable;

/**
 * @see https://open.esign.cn/doc/opendoc/identity_service/szr5s9
 * @see https://open.esign.cn/doc/opendoc/saas_api/yiiorw
 */
class AccessToken
{
    use HasHttpRequests;
    use ResponseCastable;
    use InteractsWithCache;

    /**
     * @var Application $app
     */
    protected $app;

    /**
     * @var array
     */
    protected $token;

    /**
     * @var string
     */
    protected $endpointToGetToken = '/v1/oauth2/access_token';

    /**
     * @var string
     */
    protected $requestMethod = 'GET';

    /**
     * @var string
     */
    protected $tokenKey = 'token';

    /**
     * @var string
     */
    protected $cachePrefix = 'esign.kernel.access_token.';

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $token
     * @param int $lifetime
     * @return $this
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \QF\LaravelEsign\Kernel\Exceptions\InvalidArgumentException
     */
    public function setToken(string $token, int $lifetime = 7200)
    {
        $this->getCache()->set($this->getCacheKey(), [
            $this->tokenKey => $token,
            'expires_in' => $lifetime,
        ], $lifetime);

        if (!$this->getCache()->has($this->getCacheKey())) {
            throw new RuntimeException('Failed to cache access token.');
        }

        return $this;
    }

    /**
     * @param bool $refresh
     * @return array
     * @throws BadResponseException
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \QF\LaravelEsign\Kernel\Exceptions\InvalidArgumentException
     */
    public function getToken(bool $refresh = false): array
    {
        $cacheKey = $this->getCacheKey();
        $cache = $this->getCache();

        if (!$refresh && $cache->has($cacheKey) && $result = $cache->get($cacheKey)) {
            return $result;
        }

        /** @var array $token */
        $token = $this->requestToken($this->getCredentials(), true);

        $this->setToken($token[$this->tokenKey], $token['expires_in'] ?? 7200);

        $this->token = $token;

        return $token;
    }

    /**
     * @return $this
     * @throws BadResponseException
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \QF\LaravelEsign\Kernel\Exceptions\InvalidArgumentException
     */
    public function refresh()
    {
        $this->getToken(true);

        return $this;
    }

    /**
     * @param array $credentials
     * @param $toArray
     * @return array|mixed|object|ResponseInterface|string
     * @throws HttpException
     * @throws BadResponseException
     */
    public function requestToken(array $credentials, $toArray = false)
    {
        $response = $this->sendRequest($credentials);
        $result = json_decode($response->getBody()->getContents(), true);

        if (empty($result['data']) || empty($result['data'][$this->tokenKey])){
            throw new HttpException('Failed to get access_token:' . json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        $formatted = $this->castResponseToType($response, $this->app['config']->get('response_type'));

        return $toArray ? $result['data'] : $formatted;
    }

    /**
     * @param RequestInterface $request
     * @param array $requestOptions
     * @return RequestInterface
     * @throws BadResponseException
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \QF\LaravelEsign\Kernel\Exceptions\InvalidArgumentException
     */
    public function applyToRequest(RequestInterface $request, array $requestOptions = []): RequestInterface
    {
        //@todo 请求签名鉴权
        return $request->withHeader('X-Tsign-Open-App-Id', $this->app->config->app_id)
            ->withHeader('X-Tsign-Open-Token', $this->getToken()['token'])
            ->withHeader('Content-Type', 'application/json;charset=UTF-8');
    }


    public function getEndpoint(): string
    {
        return $this->endpointToGetToken;
    }

    protected function sendRequest(array $credentials): ResponseInterface
    {
        $options = [
            ('GET' === $this->requestMethod) ? 'query' : 'json' => $credentials,
        ];

        return $this->setHttpClient($this->app['http_client'])->request($this->getEndpoint(), $this->requestMethod, $options);
    }

    protected function getCredentials(): array
    {
        return [
            'grantType' => 'client_credentials',
            'appId' => $this->app['config']['app_id'],
            'secret' => $this->app['config']['secret'],
        ];
    }

    protected function getCacheKey()
    {
        return $this->cachePrefix.md5(json_encode($this->getCredentials()));
    }
}