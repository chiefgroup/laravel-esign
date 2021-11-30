<?php

/*
 * This file is part of the chiefgroup/laravel-esign.
 *
 * (c) peng <2512422541@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace QF\LaravelEsign\Identity;

use QF\LaravelEsign\Core\AbstractAPI;
use QF\LaravelEsign\Exceptions\HttpException;
use QF\LaravelEsign\Support\Collection;

class Identity extends AbstractAPI
{

    // 认证Api
    const ORG_IDENTITY_URL = '/v2/identity/auth/web/%s/orgIdentityUrl';                                    // 获取组织机构实名认证地址
    const CHECK_BANK_CARD_4FACTORS = '/v2/identity/auth/api/individual/bankCard4Factors';                  // 银行卡4要素核身校验
    const CHECK_BANK_MOBILE_AUTH_CODE = '/v2/identity/auth/pub/individual/%s/bankCard4Factors';      // 银行预留手机号验证码检验

    /**
     * @param  string  $orgId  机构 id
     * @param  string  $agentAccountId  办理人账号Id
     * @param  string  $notifyUrl  发起方接收实名认证状态变更通知的地址
     * @param  string  $redirectUrl  实名结束后页面跳转地址
     * @param  string  $contextId  发起方业务上下文标识
     * @param  string  $authType  指定默认认证类型
     * @param  bool  $repeatIdentity  是否允许重复实名，默认允许
     * @param  bool  $showResultPage  实名完成是否显示结果页,默认显示
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function getOrgIdentityUrl($orgId, $agentAccountId, $notifyUrl = '', $redirectUrl = '', $contextId = '', $authType = '', $repeatIdentity = true, $showResultPage = true)
    {
        $url = sprintf(self::ORG_IDENTITY_URL, $orgId);

        $params = [
            'authType'       => $authType,
            'repeatIdentity' => $repeatIdentity,
            'agentAccountId' => $agentAccountId,
            'contextInfo'    => [
                'contextId'      => $contextId,
                'notifyUrl'      => $notifyUrl,
                'redirectUrl'    => $redirectUrl,
                'showResultPage' => $showResultPage,
            ],
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * @param  string  $name  用户真实姓名
     * @param  string  $idNo  身份证号
     * @param  string  $mobileNo  用户在银行预留的手机号
     * @param  string  $bankCardNo  银行卡号
     * @param  string  $contextId  发起方业务上下文标识
     * @param  string  $notifyUrl  认证结束后异步通知地址
     * @param  string  $certType  个人证件类型 不传默认为身份证
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function verifyBankCard4Factors($name, $idNo, $mobileNo = '', $bankCardNo = '', $contextId = '', $certType = '', $notifyUrl = '')
    {
        $url = self::CHECK_BANK_CARD_4FACTORS;

        $params = [
            'name'       => $name,
            'contextId'  => $contextId,
            'idNo'       => $idNo,
            'mobileNo'   => $mobileNo,
            'bankCardNo' => $bankCardNo,
            'certType'   => $certType,
            'notifyUrl'  => $notifyUrl,
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * @param  string  $flowId  实名认证流程Id
     * @param  string  $authcode  短信验证码，用户收到的6位数字验证码
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function verifyAuthCodeOfMobile($flowId, $authcode)
    {
        $url = sprintf(self::CHECK_BANK_MOBILE_AUTH_CODE, $flowId);

        $params = ['authcode' => $authcode];

        return $this->parseJSON('put', [$url, $params]);
    }
}
