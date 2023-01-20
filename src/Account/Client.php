<?php

namespace QF\LaravelEsign\Account;

use QF\LaravelEsign\ConstMapping;
use QF\LaravelEsign\Kernel\BaseClient;
use QF\LaravelEsign\Kernel\Exceptions\BadResponseException;

class Client extends BaseClient
{
    /**
     * @param string $thirdPartyUserId
     * @param string|null $mobile
     * @param string|null $name
     * @param string|null $idNumber
     * @param string|null $email
     * @param string $idType
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws BadResponseException
     */
    public function createPersonalAccount(
        string $thirdPartyUserId,
        string $mobile = null,
        string $name = null,
        string $idNumber = null,
        string $email = null,
        string $idType = 'CRED_PSN_CH_IDCARD'
    ) {
        return $this->httpPostJson('/v1/accounts/createByThirdPartyUserId', [
            'thirdPartyUserId' => $thirdPartyUserId,
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
            'mobile' => $mobile,
            'email' => $email,
        ]);
    }

    /**
     * @param $thirdId
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws BadResponseException
     */
    public function queryPersonalAccountByThirdId($thirdId)
    {
        return $this->httpGet('/v1/accounts/getByThirdId', [
            'thirdPartyUserId' => $thirdId,
        ]);
    }

    /**
     * @param $accountId
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function queryPersonalAccountByAccountId($accountId)
    {
        return $this->httpGet("/v1/accounts/{$accountId}");
    }

    /**
     * @param string $thirdPartyUserId
     * @param $name
     * @param $idNumber
     * @param $idType
     * @param $mobile
     * @param $email
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updatePersonalAccountByThirdId(
        string $thirdPartyUserId,
        $name = null,
        $idNumber = null,
        $idType = null,
        $mobile = null,
        $email = null
    ) {
        $params = [
            'mobile' => $mobile,
            'email' => $email,
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
        ];

        return $this->httpPostJson('/v1/accounts/updateByThirdId', $params, ['thirdPartyUserId' => $thirdPartyUserId]);
    }

    /**
     * @param string $accountId
     * @param $name
     * @param $idNumber
     * @param $idType
     * @param $mobile
     * @param $email
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updatePersonalAccountByAccountId(
        string $accountId,
        $name = null,
        $idNumber = null,
        $idType = null,
        $mobile = null,
        $email = null
    ) {
        $params = [
            'mobile' => $mobile,
            'email' => $email,
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
        ];
        return $this->request("/v1/accounts/{$accountId}", 'put', ['json' => $params]);
    }

    /**
     * @param $thirdPartyUserId
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deletePersonalAccountByThirdId($thirdPartyUserId)
    {
        return $this->request(
            '/v1/accounts/deleteByThirdId',
            'delete',
            ['query' => ['thirdPartyUserId' => $thirdPartyUserId]]
        );
    }

    /**
     * @param $accountId
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deletePersonalAccountByAccountId($accountId)
    {
        return $this->request("/v1/accounts/{$accountId}", 'delete');
    }


    /**
     *
     * 创建机构签署账号
     *
     * @param string $thirdPartyUserId
     * @param string $creatorAccountId
     * @param string $name
     * @param string $idNumber
     * @param string $idType
     * @param string|null $orgLegalIdNumber
     * @param string|null $orgLegalName
     * @return \Psr\Http\Message\ResponseInterface
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createOrganizeAccount(
        string $thirdPartyUserId,
        string $creatorAccountId,
        string $name,
        string $idNumber,
        string $orgLegalIdNumber = null,
        string $orgLegalName = null,
        string $idType = ConstMapping::CRED_ORG_USCC
    ) {
        $params = [
            'thirdPartyUserId' => $thirdPartyUserId,
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
            'creator' => $creatorAccountId,
            'orgLegalIdNumber' => $orgLegalIdNumber,
            'orgLegalName' => $orgLegalName,
        ];

        return $this->httpPostJson('/v1/organizations/createByThirdPartyUserId', $params);
    }

    /**
     * @param string $thirdPartyUserId
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function queryOrganizeAccountByThirdId(string $thirdPartyUserId)
    {
        return $this->httpget('/v1/organizations/getByThirdId', ['thirdPartyUserId' => $thirdPartyUserId]);
    }

    /**
     * @param string $orgId
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function queryOrganizeAccountByOrgId(string $orgId)
    {
        return $this->httpget("/v1/organizations/{$orgId}");
    }

    /**
     * @param string $thirdPartyUserId
     * @param string|null $name
     * @param string|null $idNumber
     * @param string|null $idType
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateOrganizeAccountByThirdId(
        string $thirdPartyUserId,
        string $name = null,
        string $idNumber = null,
        string $idType = null
    ) {
        $params = [
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
        ];

        return $this->httpPostJson(
            '/v1/organizations/updateByThirdId',
            $params,
            ['thirdPartyUserId' => $thirdPartyUserId]
        );
    }

    /**
     * @param string $orgId
     * @param $name
     * @param $idNumber
     * @param $orgLegalIdNumber
     * @param $orgLegalName
     * @param $idType
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateOrganizeAccountById(string $orgId, $name = null, $idNumber = null, $orgLegalIdNumber = null, $orgLegalName = null, $idType = null)
    {
        $params = [
            'name'             => $name,
            'idNumber'         => $idNumber,
            'idType'           => $idType,
            'orgLegalIdNumber' => $orgLegalIdNumber,
            'orgLegalName'     => $orgLegalName,
        ];

        return $this->httpPostJson("/v1/organizations/{$orgId}", $params);
    }

    /**
     * @param string $thirdPartyUserId
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteOrganizeAccountByThirdId(string $thirdPartyUserId)
    {
        return $this->request(
            '/v1/organizations/deleteByThirdId',
            'delete',
            ['query' => ['thirdPartyUserId' => $thirdPartyUserId]]
        );
    }

    /**
     * @param string $orgId
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteOrganizeAccountByOrgId(string $orgId)
    {
        return $this->request("/v1/organizations/{$orgId}", 'delete');
    }

    /**
     * 设置静默签署.
     *
     * @param string $accountId
     * @param $deadline
     * @return \Psr\Http\Message\ResponseInterface
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setSignAuth(string $accountId, $deadline = null)
    {
        $params = [
            'deadline' => $deadline,
        ];

        return $this->httpPostJson("/v1/signAuth/{$accountId}", $params);
    }

    /**
     * 撤销静默签署.
     *
     * @param string $accountId
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteSignAuth(string $accountId)
    {
        return $this->request("/v1/signAuth/{$accountId}", 'delete');
    }

    /**
     * ========================
     * V3
     * ========================
     */

    public function psnAuthUrl(string $psnAccount, array $psnInfo)
    {
        return $this->httpPostJson('/v3/psn-auth-url', [
            'psnAuthConfig' => [
                'psnAccount' => $psnAccount,
                'psnInfo' => $psnInfo,
                'psnAuthPageConfig' => [
                    'psnDefaultAuthMode' => 'PSN_MOBILE3',
                    'psnAvailableAuthModes' => ['PSN_BANKCARD4','PSN_MOBILE3','PSN_FACE']
                ]
            ],
            'authorizeConfig' => [
                'authorizedScopes' => ['get_psn_identity_info', 'psn_initiate_sign', 'manage_psn_resource']
            ],
        ]);
    }

    public function psnAuthFlow(string $authFlowId)
    {
        return $this->httpGet("/v3/auth-flow/{$authFlowId}");
    }
}
