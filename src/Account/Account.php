<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Account;

use XNXK\LaravelEsign\Core\AbstractAPI;
use XNXK\LaravelEsign\Exceptions\HttpException;
use XNXK\LaravelEsign\Support\Collection;

class Account extends AbstractAPI
{
    // API URL
    public const CREATE_PERSONAL_ACCOUNT = '/v1/accounts/createByThirdPartyUserId';                // 创建个人账户
    public const UPDATE_ACCOUNT_BY_THIRD_ID = '/v1/accounts/updateByThirdId?thirdPartyUserId=%s';  // 个人账户修改(按照第三方用户ID修改)
    public const QUERY_ACCOUNT_BY_THIRD_ID = '/v1/accounts/getByThirdId';                          // 查询个人账户（按照第三方用户ID查询）
    public const DEL_ACCOUNT_BY_THIRD_ID = '/v1/accounts/deleteByThirdId?thirdPartyUserId=%s';     // 注销个人账户（按照第三方用户ID查询）
    public const SET_ACCOUNT_SIGN_PAW = '/v1/accounts/%s/setSignPwd';                              // 设置签署密码
    public const ACCOUNT_BY_ID = '/v1/accounts/%s';                                                // 个人账户修改\查询个人账户\注销个人账户(按照账号ID)
    public const ORG_BY_ID = '/v1/organizations/%s';                                               // 机构账户修改\查询机构账号\注销机构账号(按照账号ID)
    public const CREATE_COMPANY_ACCOUNT = '/v1/organizations/createByThirdPartyUserId';            // 创建企业账户
    public const UPDATE_ORG_BY_THIRD_ID = '/v1/organizations/updateByThirdId?thirdPartyUserId=%s'; // 机构账号修改（按照第三方机构ID修改）
    public const QUERY_ORG_BY_THIRD_ID = '/v1/organizations/getByThirdId?thirdPartyUserId=%s';     // 查询机构账号（按照第三方机构ID查询）
    public const DEL_ORG_BY_THIRD_ID = '/v1/organizations/deleteByThirdId?thirdPartyUserId=%s';    // 注销机构账号（按照账号ID注销）
    public const SIGN_AUTH = '/v1/signAuth/%s';                                                    // 设置静默签署/撤销静默签署

    /**
     * 创建个人账号.
     *
     * @param  string  $thirdPartyUserId  用户唯一标识
     * @param  string  $name  姓名
     * @param  string  $idType  证件类型, 默认: CRED_PSN_CH_IDCARD
     * @param  string  $idNumber  证件号
     * @param  string  $mobile  手机号码
     * @param  string  $email  邮箱地址
     *
     * @throws HttpException
     */
    public function createPersonalAccount(string $thirdPartyUserId, ?string $mobile = null, ?string $name = null, ?string $idNumber = null, ?string $email = null, string $idType = 'CRED_PSN_CH_IDCARD'): ?Collection
    {
        $params = [
            'thirdPartyUserId' => $thirdPartyUserId,
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
            'mobile' => $mobile,
            'email' => $email,
        ];

        return $this->parseJSON('json', [self::CREATE_PERSONAL_ACCOUNT, $params]);
    }

    /**
     * 个人账户修改(按照账号ID修改).
     *
     * @param  string  $accountId  个人账号id
     * @param  null  $email  联系方式，邮箱地址
     * @param  null  $mobile  联系方式，手机号码
     * @param  null  $name  姓名，默认不变
     * @param  null  $idType  证件类型，默认为身份证
     * @param  null  $idNumber  证件号，该字段只有为空才允许修改
     *
     * @throws HttpException
     */
    public function updatePersonalAccountById(string $accountId, $mobile = null, $idNumber = null, $name = null, $idType = null, $email = null): ?Collection
    {
        $url = sprintf(self::ACCOUNT_BY_ID, $accountId);
        $params = [
            'mobile' => $mobile,
            'email' => $email,
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * 个人账户修改(按照第三方用户ID修改).
     *
     * @param  string  $thirdPartyUserId  第三方平台的用户唯一标识
     * @param  null  $email  联系方式，邮箱地址
     * @param  null  $mobile  联系方式，手机号码
     * @param  null  $name  姓名，默认不变
     * @param  null  $idType  证件类型，默认为身份证
     * @param  null  $idNumber  证件号，该字段只有为空才允许修改
     *
     * @throws HttpException
     */
    public function updatePersonalAccountByThirdId(string $thirdPartyUserId, $mobile = null, $idNumber = null, $name = null, $idType = null, $email = null): ?Collection
    {
        $url = sprintf(self::UPDATE_ACCOUNT_BY_THIRD_ID, $thirdPartyUserId);
        $params = [
            'mobile' => $mobile,
            'email' => $email,
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * 查询个人账户（按照账户ID查询）.
     *
     * @param  string  $accountId  个人账号id
     *
     * @throws HttpException
     */
    public function queryPersonalAccountByAccountId(string $accountId): ?Collection
    {
        $url = sprintf(self::ACCOUNT_BY_ID, $accountId);

        return $this->parseJSON('get', [$url]);
    }

    /**
     * 查询个人账户（按照第三方用户ID查询）.
     *
     * @param  string  $thirdId  第三方平台的用户id
     *
     * @throws HttpException
     */
    public function queryPersonalAccountByThirdId(string $thirdId): ?Collection
    {
        $params = [
            'thirdPartyUserId' => $thirdId,
        ];

        return $this->parseJSON('get', [self::QUERY_ACCOUNT_BY_THIRD_ID, $params]);
    }

    /**
     * 注销个人账户（按照账号ID注销）.
     *
     * @param  string  $accountId  个人账号id
     *
     * @throws HttpException
     */
    public function deletePersonalAccountById(string $accountId): ?Collection
    {
        $url = sprintf(self::ACCOUNT_BY_ID, $accountId);

        return $this->parseJSON('delete', [$url]);
    }

    /**
     * 注销个人账户（按照第三方用户ID注销）.
     *
     * @param  string  $thirdPartyUserId  第三方平台的用户id
     *
     * @throws HttpException
     */
    public function deletePersonalAccountByThirdId(string $thirdPartyUserId): ?Collection
    {
        $url = sprintf(self::DEL_ACCOUNT_BY_THIRD_ID, $thirdPartyUserId);

        return $this->parseJSON('delete', [$url]);
    }

    /**
     * 设置签署密码.
     *
     * @param  string  $accountId  用户id
     * @param  string  $password  MD5加密后的密文
     *
     * @throws HttpException
     */
    public function setSignPwd(string $accountId, string $password): ?Collection
    {
        $url = sprintf(self::SET_ACCOUNT_SIGN_PAW, $accountId);
        $params = [
            'password' => $password,
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * 机构账号创建.
     *
     * @param  string  $thirdPartyUserId  string 第三方平台标识, 如: 统一信用代码
     * @param  string  $creatorAccountId  string 创建者 accountId
     * @param  string  $name  string 机构名称
     * @param  string  $idType  string 证件类型, 默认: CRED_ORG_USCC
     * @param  string  $idNumber  string 证件号
     * @param  null  $orgLegalIdNumber  string 企业法人证件号
     * @param  null  $orgLegalName  string 企业法人名称
     *
     * @throws HttpException
     */
    public function createOrganizeAccount(string $thirdPartyUserId, string $creatorAccountId, string $name, ?string $idNumber = null, $orgLegalIdNumber = null, $orgLegalName = null, string $idType = 'CRED_ORG_USCC'): ?Collection
    {
        $params = [
            'thirdPartyUserId' => $thirdPartyUserId,
            'creator' => $creatorAccountId,
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
            'orgLegalIdNumber' => $orgLegalIdNumber,
            'orgLegalName' => $orgLegalName,
        ];

        return $this->parseJSON('json', [self::CREATE_COMPANY_ACCOUNT, $params]);
    }

    /**
     * 机构账号修改（按照账号ID修改）.
     *
     * @param  string  $orgId  机构账号id
     * @param  null  $name  机构名称，默认不变
     * @param  null  $idType  证件类型，默认CRED_ORG_USCC
     * @param  null  $idNumber  证件号
     * @param  null  $orgLegalIdNumber  企业法人证件号
     * @param  null  $orgLegalName  企业法人名称
     *
     * @throws HttpException
     */
    public function updateOrganizeAccountById(string $orgId, $name = null, $idNumber = null, $orgLegalIdNumber = null, $orgLegalName = null, $idType = null): ?Collection
    {
        $url = sprintf(self::ORG_BY_ID, $orgId);
        $params = [
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
            'orgLegalIdNumber' => $orgLegalIdNumber,
            'orgLegalName' => $orgLegalName,
        ];

        return $this->parseJSON('put', [$url, $params]);
    }

    /**
     * 机构账号修改（按照第三方机构ID修改）.
     *
     * @param  string  $thirdPartyUserId  第三方平台机构id
     * @param  null  $name  机构名称，默认不变
     * @param  null  $idType  证件类型，默认CRED_ORG_USCC
     * @param  null  $idNumber  证件号
     *
     * @throws HttpException
     */
    public function updateOrganizeAccountByThirdId(string $thirdPartyUserId, $name = null, $idNumber = null, $idType = null): ?Collection
    {
        $url = sprintf(self::UPDATE_ORG_BY_THIRD_ID, $thirdPartyUserId);
        $params = [
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
        ];

        return $this->parseJSON('put', [$url, $params]);
    }

    /**
     * 查询机构账号（按照账号ID查询）.
     *
     * @param  string  $orgId  机构账号id
     *
     * @throws HttpException
     */
    public function queryOrganizeAccountByOrgId(string $orgId): ?Collection
    {
        $url = sprintf(self::ORG_BY_ID, $orgId);

        return $this->parseJSON('get', [$url]);
    }

    /**
     * 查询机构账号（按照第三方机构ID查询）.
     *
     * @param  string  $thirdPartyUserId  第三方平台机构id
     *
     * @throws HttpException
     */
    public function queryOrganizeAccountByThirdId(string $thirdPartyUserId): ?Collection
    {
        $url = sprintf(self::QUERY_ORG_BY_THIRD_ID, $thirdPartyUserId);

        return $this->parseJSON('get', [$url]);
    }

    /**
     * 注销机构账号（按照账号ID注销）.
     *
     * @param  string  $orgId  机构账号id
     *
     * @throws HttpException
     */
    public function deleteOrganizeAccountByOrgId(string $orgId): ?Collection
    {
        $url = sprintf(self::ORG_BY_ID, $orgId);

        return $this->parseJSON('delete', [$url]);
    }

    /**
     * 注销机构账号（按照账号ID注销）.
     *
     * @param  string  $thirdPartyUserId  第三方平台的机构id
     *
     * @throws HttpException
     */
    public function deleteOrganizeAccountByThirdId(string $thirdPartyUserId): ?Collection
    {
        $url = sprintf(self::DEL_ORG_BY_THIRD_ID, $thirdPartyUserId);

        return $this->parseJSON('delete', [$url]);
    }

    /**
     * 设置静默签署.
     *
     * @param  string  $accountId  授权人id，即个人账号id或机构账号id
     * @param  null  $deadline
     *
     * @throws HttpException
     */
    public function setSignAuth(string $accountId, $deadline = null): ?Collection
    {
        $url = sprintf(self::SIGN_AUTH, $accountId);

        $params = [
            'deadline' => $deadline,
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * 撤销静默签署.
     *
     * @param  string  $accountId  授权人id，即个人账号id或机构账号id
     *
     * @throws HttpException
     */
    public function deleteSignAuth(string $accountId): ?Collection
    {
        $url = sprintf(self::SIGN_AUTH, $accountId);

        return $this->parseJSON('delete', [$url]);
    }
}
