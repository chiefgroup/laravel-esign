<?php

namespace QF\LaravelEsign\Account;

use QF\LaravelEsign\Kernel\BaseClient;

class Client extends BaseClient
{
    public function createPersonalAccount(
        string $thirdPartyUserId,
        string $name = null,
        string $idNumber = null,
        string $idType = 'CRED_PSN_CH_IDCARD',
        string $mobile = null,
        string $email = null
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

    public function queryPersonalAccountByThirdId($thirdId)
    {
        return $this->httpGet('/v1/accounts/getByThirdId', [
            'thirdPartyUserId' => $thirdId,
        ]);
    }

    public function queryPersonalAccountByAccountId($accountId)
    {
        return $this->httpGet("/v1/accounts/{$accountId}");
    }

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

    public function deletePersonalAccountByThirdId($thirdPartyUserId)
    {
        return $this->request(
            '/v1/accounts/deleteByThirdId',
            'delete',
            ['query' => ['thirdPartyUserId' => $thirdPartyUserId]]
        );
    }

    public function deletePersonalAccountByAccountId($accountId)
    {
        return $this->request("/v1/accounts/{$accountId}", 'delete');
    }


    /**
     *
     * 创建机构签署账号
     *
     * @param string $thirdPartyUserId
     * @param string $name
     * @param string $idNumber
     * @param string $idType
     * @param string|null $orgLegalIdNumber
     * @param string|null $orgLegalName
     * @param string|null $creatorAccountId
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createOrganizeAccount(
        string $thirdPartyUserId,
        string $name,
        string $idNumber,
        string $idType = 'CRED_ORG_USCC',
        string $orgLegalIdNumber = null,
        string $orgLegalName = null,
        string $creatorAccountId = null
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

    public function queryOrganizeAccountByThirdId($thirdPartyUserId)
    {
        return $this->httpget('/v1/organizations/getByThirdId', ['thirdPartyUserId' => $thirdPartyUserId]);
    }

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

    public function deleteOrganizeAccountByThirdId($thirdPartyUserId)
    {
        return $this->request(
            '/v1/organizations/deleteByThirdId',
            'delete',
            ['query' => ['thirdPartyUserId' => $thirdPartyUserId]]
        );
    }

    /**
     * 设置静默签署.
     *
     * @param string $accountId
     * @param $deadline
     * @return \Psr\Http\Message\ResponseInterface
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
