<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Template;

use XNXK\LaravelEsign\Core\AbstractAPI;
use XNXK\LaravelEsign\Exceptions\HttpException;
use XNXK\LaravelEsign\Support\Collection;

class Template extends AbstractAPI
{
    // API URL
    const CREATE_PERSONAL_TEMPLATE = '/v1/accounts/%s/seals/personaltemplate';          // 创建个人模板印章
    const CREATE_OFFICIAL_TEMPLATE = '/v1/organizations/%s/seals/officialtemplate';     // 创建机构模板印章
    const CREATE_IMAGE_TEMPLATE = '/v1/accounts/%s/seals/image';                        // 创建个人/机构图片印章
    const SET_ACCOUNT_DEFAULT_TEMPLATE = '/v1/accounts/%s/seals/%s/setDefault';         // 设置个人默认印章
    const SET_ORG_DEFAULT_TEMPLATE = '/v1/organizations/%s/seals/%s/setDefault';        // 设置机构默认印章
    const QUERY_ACCOUNT_TEMPLATE = '/v1/accounts/%s/seals';                             // 查询个人印章
    const QUERY_ORG_TEMPLATE = '/v1/organizations/%s/seals';                            // 查询机构印章
    const DEL_ACCOUNT_TEMPLATE = '/v1/accounts/%s/seals/%s';                            // 删除个人印章
    const DEL_ORG_TEMPLATE = '/v1/organizations/%s/seals/%s';                           // 删除机构印章

    /**
     * 创建个人模板印章.
     *
     * @param  string  $accountId  用户id
     * @param  string  $alias  印章别名
     * @param  string  $color  印章颜色
     * @param  int  $height  印章高度
     * @param  int  $width  印章宽度
     * @param  string  $type  模板类型
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function createPersonalTemplate($accountId, $alias = '', $color = 'RED', $height = 95, $width = 95, $type = 'SQUARE')
    {
        $url = sprintf(self::CREATE_PERSONAL_TEMPLATE, $accountId);
        $params = [
            'alias'  => $alias,
            'color'  => $color,
            'height' => $height,
            'width'  => $width,
            'type'   => $type,
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * 创建机构模板印章.
     *
     * @param  string  $orgId  机构id
     * @param  string  $alias  印章别名
     * @param  string  $color  印章颜色
     * @param  int  $height  印章高度
     * @param  int  $width  印章宽度
     * @param  string  $htext  横向文
     * @param  string  $qtext  下弦文
     * @param  string  $type  模板类型
     * @param  string  $central  中心图案类型
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function createOfficialTemplate($orgId, $alias = '', $color = 'RED', $height = 159, $width = 159, $htext = '', $qtext = '', $type = 'TEMPLATE_ROUND', $central = 'STAR')
    {
        $url = sprintf(self::CREATE_OFFICIAL_TEMPLATE, $orgId);
        $params = [
            'alias'   => $alias,
            'color'   => $color,
            'height'  => $height,
            'width'   => $width,
            'htext'   => $htext,
            'qtext'   => $qtext,
            'type'    => $type,
            'central' => $central,
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * 创建个人/机构图片印章.
     *
     * @param  string  $accountId  用户id
     * @param  string  $data  印章数据
     * @param  string  $alias  印章别名
     * @param  int  $height  印章高度
     * @param  int  $width  印章宽度
     * @param  string  $type  印章数据类型 BASE64
     * @param  bool  $transparentFlag  是否对图片进行透明化处理
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function createImageTemplate($accountId, $data, $alias = '', $height = 95, $width = 95, $type = 'TEMPLATE_ROUND', $transparentFlag = false)
    {
        $url = sprintf(self::CREATE_IMAGE_TEMPLATE, $accountId);
        $params = [
            'alias'           => $alias,
            'height'          => $height,
            'width'           => $width,
            'type'            => $type,
            'data'            => $data,
            'transparentFlag' => $transparentFlag,
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * 设置个人默认印章.
     *
     * @param  string  $accountId  用户id
     * @param  string  $sealId  印章id
     * @return Collection|null
     * @throws HttpException
     */
    public function setAccountDefaultTemplate(string $accountId, string $sealId = '')
    {
        $url = sprintf(self::SET_ACCOUNT_DEFAULT_TEMPLATE, $accountId, $sealId);

        return $this->parseJSON('put', [$url, []]);
    }

    /**
     * 设置机构默认印章.
     *
     * @param  string  $orgId  用户id
     * @param  string  $sealId  印章id
     * @return Collection|null
     * @throws HttpException
     */
    public function setOrgDefaultTemplate(string $orgId, string $sealId = '')
    {
        $url = sprintf(self::SET_ORG_DEFAULT_TEMPLATE, $orgId, $sealId);

        return $this->parseJSON('put', [$url, []]);
    }

    /**
     * 查询个人印章.
     *
     * @param  string  $accountId  用户id
     * @param  int  $offset  分页起始位置
     * @param  int  $size  单页数量
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function queryPersonalTemplates($accountId, $offset = 0, $size = 10)
    {
        $url = sprintf(self::QUERY_ACCOUNT_TEMPLATE, $accountId);
        $params = [
            'offset' => $offset,
            'size'   => $size,
        ];

        return $this->parseJSON('get', [$url, $params]);
    }

    /**
     * 查询机构印章.
     *
     * @param  string  $orgId  机构id
     * @param  int  $offset  分页起始位置
     * @param  int  $size  单页数量
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function queryOfficialTemplates($orgId, $offset = 0, $size = 10)
    {
        $url = sprintf(self::QUERY_ORG_TEMPLATE, $orgId);
        $params = [
            'offset' => $offset,
            'size'   => $size,
        ];

        return $this->parseJSON('get', [$url, $params]);
    }

    /**
     * 删除个人印章.
     *
     * @param  string  $accountId  用户id
     * @param  string  $sealId  印章id
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function deletePersonalTemplate($accountId, $sealId)
    {
        $url = sprintf(self::DEL_ACCOUNT_TEMPLATE, $accountId, $sealId);

        return $this->parseJSON('delete', [$url, []]);
    }

    /**
     * 删除机构印章.
     *
     * @param  string  $orgId  机构id
     * @param  string  $sealId  印章id
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function deleteOfficialTemplate($orgId, $sealId)
    {
        $url = sprintf(self::DEL_ORG_TEMPLATE, $orgId, $sealId);

        return $this->parseJSON('delete', [$url, []]);
    }
}
