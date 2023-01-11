<?php

namespace QF\LaravelEsign\Identity;

use QF\LaravelEsign\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * 【银行卡认证】银行卡4要素核身
     *
     * @param string $name
     * @param string $idNo
     * @param string $mobileNo
     * @param string $bankCardNo
     * @param string $certType
     * @param string $contextId
     * @param string $notifyUrl
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verifyBankCard4Factors(string $name, string $idNo, string $mobileNo, string $bankCardNo, string $certType = 'INDIVIDUAL_CH_IDCARD', string $contextId = '', string $notifyUrl = '')
    {
        $params = [
            'name' => $name,
            'contextId' => $contextId,
            'idNo' => $idNo,
            'mobileNo' => $mobileNo,
            'bankCardNo' => $bankCardNo,
            'certType' => $certType,
            'notifyUrl' => $notifyUrl,
        ];

        return $this->httpPostJson('/v2/identity/auth/api/individual/bankCard4Factors', $params);
    }

    /**
     * 【银行卡认证】预留手机号校验
     *
     * @param string $flowId
     * @param string $authcode
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verifyAuthCodeOfMobile(string $flowId, string $authcode)
    {
        return $this->request("/v2/identity/auth/pub/individual/{$flowId}/bankCard4Factors", 'put', ['json' => ['authcode' => $authcode]]);
    }


    /**
     * 获取组织机构实名认证地址
     *
     * @param string $accountId
     * @param string $agentAccountId
     * @param $notifyUrl
     * @param $redirectUrl
     * @param $contextId
     * @param $authType
     * @param $repeatIdentity
     * @param $showResultPage
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getOrgIdentityUrl(string $accountId, string $agentAccountId, $notifyUrl = '', $redirectUrl = '', $contextId = '', $authType = '', $repeatIdentity = true, $showResultPage = true)
    {
        $params = [
            'authType' => $authType,
            'repeatIdentity' => $repeatIdentity,
            'agentAccountId' => $agentAccountId,
            'contextInfo' => [
                'contextId' => $contextId,
                'notifyUrl' => $notifyUrl,
                'redirectUrl' => $redirectUrl,
                'showResultPage' => $showResultPage,
            ],
        ];

        return $this->httpPostJson("/v2/identity/auth/web/{$accountId}/orgIdentityUrl", $params);
    }
}
