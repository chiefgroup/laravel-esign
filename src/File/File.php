<?php

declare(strict_types=1);

namespace QF\LaravelEsign\File;

use QF\LaravelEsign\Core\AbstractAPI;
use QF\LaravelEsign\Exceptions\HttpException;
use QF\LaravelEsign\Support\Collection;

class File extends AbstractAPI
{
    // Api URL
    const CREATE_SIGN_DOCUMENT = '/v1/files/getUploadUrl';                          // 通过上传方式创建文件
    const CREATE_UPLOAD_URL = '/v1/docTemplates/createByUploadUrl';                 // 通过上传方式创建模板
    const ADD_DOC_TEMPLATES = '/v1/docTemplates/%s/components';                     // 通过模板添加输入项组件
    const DEL_DOC_TEMPLATES = '/v1/docTemplates/%s/components/%s';                  // 删除输入项组件
    const QUERY_DOC_TEMPLATES = '/v1/docTemplates/%s';                              // 查询模板详情/下载模板
    const CREATE_TEMPLATE = '/v1/files/createByTemplate';                           // 通过模板创建文件
    const QUERY_FILE = '/v1/files/%s';                                              // 查询文件详情/下载文件
    const ADD_WATERMARK = '/v1/files/batchAddWatermark';                            // 文件添加数字水印

    /**
     * 通过上传方式创建文件.
     *
     * @param  string  $contentMd5  先计算文件md5值，在对该md5值进行base64编码
     * @param  string  $contentType  目标文件的MIME类型，支持：（1）application/octet-stream（2）application/pdf
     * @param  bool  $convert2Pdf  是否转换成pdf文档，默认false
     * @param  string  $fileName  文件名称（必须带上文件扩展名，不然会导致后续发起流程校验过不去 示例：合同.pdf ）
     * @param  int  $fileSize  文件大小，单位byte
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function getUploadFileUrl($contentMd5, $fileName, $fileSize, $contentType = 'application/pdf', $convert2Pdf = false)
    {
        $params = [
            'contentMd5'  => $contentMd5,
            'contentType' => $contentType,
            'convert2Pdf' => $convert2Pdf,
            'fileName'    => $fileName,
            'fileSize'    => $fileSize,
        ];

        return $this->parseJSON('json', [self::CREATE_SIGN_DOCUMENT, $params]);
    }

    /**
     * 通过上传方式创建文件 && 上传文件.
     *
     * @param  string  $contentMd5  先计算文件md5值，在对该md5值进行base64编码
     * @param  string  $contentType  目标文件的MIME类型，支持：（1）application/octet-stream（2）application/pdf
     * @param  bool  $convert2Pdf  是否转换成pdf文档，默认false
     * @param  string  $fileName  文件名称（必须带上文件扩展名，不然会导致后续发起流程校验过不去 示例：合同.pdf ）
     * @param  int  $fileSize  文件大小，单位byte
     *
     * @return Collection|null
     * @throws HttpException
     */
    public function getUploadFile($filePath, $fileName, $fileSize, $contentType = 'application/pdf', $convert2Pdf = false)
    {
        $contentMd5 = $this->_getContentBase64Md5($filePath);

        $result = $this->getUploadFileUrl($contentMd5, $fileName, $fileSize, $contentType = 'application/pdf', $convert2Pdf);

        $this->_upLoadFile($result['uploadUrl'], $filePath, $contentMd5, $contentType);

        return $result;
    }

    /**
     * (模板方式)通过上传方式创建模板.
     *
     * @param  string  $contentMd5  先计算文件md5值，在对该md5值进行base64编码
     * @param  string  $contentType  目标文件的MIME类型，支持：（1）application/octet-stream（2）application/pdf
     * @param  string  $fileName  文件名称（必须带上文件扩展名，不然会导致后续发起流程校验过不去 示例：合同.pdf ）
     * @param  bool  $convert2Pdf  是否转换成pdf文档，默认false
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function createByUploadUrl($contentMd5, $fileName, $contentType = 'application/pdf', $convert2Pdf = false)
    {
        $params = [
            'contentMd5'  => $contentMd5,
            'contentType' => $contentType,
            'convert2Pdf' => $convert2Pdf,
            'fileName'    => $fileName,
        ];

        return $this->parseJSON('json', [self::CREATE_UPLOAD_URL, $params]);
    }

    /**
     * 通过上传方式创建模板 && 上传文件.
     * @param  string  $contentMd5
     * @param  string  $fileName
     * @param  string  $contentType
     * @param  bool  $convert2Pdf
     * @return Collection|null
     * @throws HttpException
     */
    public function createByUploadFile($filePath, $fileName, $contentType = 'application/pdf', $convert2Pdf = false)
    {
        $contentMd5 = $this->_getContentBase64Md5($filePath);

        $result = $this->createByUploadUrl($contentMd5, $fileName, $contentType = 'application/pdf', $convert2Pdf);

        $this->_upLoadFile($result['uploadUrl'], $filePath, $contentMd5, $contentType);

        return $result;
    }

    /**
     * 上传文件.
     * @param  string  $uploadUrls
     * @param  string  $filePath
     * @param  string  $contentMd5
     * @return mixed
     */
    private function _upLoadFile($uploadUrls, $filePath, $contentMd5, $contentType = 'application/pdf')
    {
        $fileContent = file_get_contents($filePath);

        $headers = [
            'Content-Type:' . $contentType,
            'Content-Md5:' . $contentMd5,
        ];

        $this->httpPut($uploadUrls, $fileContent, $headers);
    }

    /**
     *  获取文件的Content-MD5
     *  原理：
     *  1.先计算MD5加密的二进制数组（128位）
     *  2.再对这个二进制进行base64编码（而不是对32位字符串编码）.
     *
     * @param $filePath
     * @return string
     */
    private function _getContentBase64Md5($filePath)
    {
        //获取文件MD5的128位二进制数组
        $md5file = md5_file($filePath, true);
        //计算文件的Content-MD5
        return base64_encode($md5file);
    }

    /**
     * 添加输入项组件.
     *
     * @param  string  $templateId  模板id
     * @param  int  $type  输入项组件类型，1-文本，2-数字,3-日期，6-签约区
     * @param  string  $label  输入项组件显示名称
     * @param  float  $width  输入项组件宽度
     * @param  float  $height  输入项组件高度
     * @param  int  $page  页码
     * @param  float  $x  x轴坐标，左下角为原点
     * @param  float  $y  y轴坐标，左下角为原点
     * @param  int  $font  填充字体,默认1，1-宋体，2-新宋体，3-微软雅黑，4-黑体，5-楷体
     * @param  int  $fontSize  填充字体大小,默认12
     * @param  string  $textColor  字体颜色，默认#000000黑色
     * @param  null  $id  输入项组件id，使用时可用id填充，为空时表示添加，不为空时表示修改
     * @param  null  $key  模板下输入项组件唯一标识，使用模板时也可用根据key值填充
     * @param  bool  $required  是否必填，默认true
     * @param  null  $limit  输入项组件type=2,type=3时填充格式校验规则;数字格式如：# 或者 #00.0# 日期格式如： yyyy-MM-dd
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function createInputOption(
        $templateId,
        $type,
        $label,
        $width,
        $height,
        $page,
        $x,
        $y,
        $font = 1,
        $fontSize = 12,
        $textColor = '#000000',
        $id = null,
        $key = null,
        $required = true,
        $limit = null
    ) {
        $url = sprintf(self::ADD_DOC_TEMPLATES, $templateId);

        $params = [
            'structComponent' => [
                'id'      => $id,
                'key'     => $key,
                'type'    => $type,
                'context' => [
                    'label'    => $label,
                    'required' => $required,
                    'limit'    => $limit,
                    'style'    => [
                        'width'     => $width,
                        'height'    => $height,
                        'font'      => $font,
                        'fontSize'  => $fontSize,
                        'textColor' => $textColor,
                    ],
                    'pos'      => [
                        'page' => $page,
                        'x'    => $x,
                        'y'    => $y,
                    ],
                ],
            ],
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * 删除输入项组件.
     *
     * @param  string  $templateId  模板id
     * @param  string  $ids  输入项组件id集合，逗号分隔
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function deleteInputOptions($templateId, $ids)
    {
        $url = sprintf(self::DEL_DOC_TEMPLATES, $templateId, $ids);

        return $this->parseJSON('delete', [$url]);
    }

    /**
     * 查询模板详情/下载模板.
     *
     * @param  string  $templateId  模板id
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function downloadDocTemplate($templateId)
    {
        $url = sprintf(self::QUERY_DOC_TEMPLATES, $templateId);

        return $this->parseJSON('get', [$url]);
    }

    /**
     * (模板方式)通过模板创建文件.
     *
     * @param  string  $templateId  模板编号
     * @param  string  $name  文件名称（必须带上文件扩展名，不然会导致后续发起流程校验过不去 示例：合同.pdf ）；
     * @param  string  $simpleFormFields  输入项填充内容，key:value 传入
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function createByTemplate($templateId, $name, $simpleFormFields)
    {
        $params = [
            'name'             => $name,
            'templateId'       => $templateId,
            'simpleFormFields' => $simpleFormFields,
        ];

        return $this->parseJSON('json', [self::CREATE_TEMPLATE, $params]);
    }

    /**
     * 查询文件详情/下载文件.
     *
     * @param  string  $fileId  文件id
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function downloadFile($fileId)
    {
        $url = sprintf(self::QUERY_FILE, $fileId);

        return $this->parseJSON('get', [$url]);
    }

    /**
     * 文件添加数字水印.
     *
     * @param  array  $files  文件信息
     * @param  string|null  $notifyUrl  水印图片全部添加完成回调地址
     * @param  string|null  $thirdOrderNo  三方流水号（唯一），有回调必填
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function batchAddWatermark($files, $notifyUrl = null, $thirdOrderNo = null)
    {
        $params = [
            'files'        => $files,
            'notifyUrl'    => $notifyUrl,
            'thirdOrderNo' => $thirdOrderNo,
        ];

        return $this->parseJSON('json', [self::ADD_WATERMARK, $params]);
    }
}
