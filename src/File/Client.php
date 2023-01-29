<?php

namespace QF\LaravelEsign\File;

use QF\LaravelEsign\Kernel\BaseClient;
use QF\LaravelEsign\Kernel\Exceptions\BadResponseException;

class Client extends BaseClient
{
    /**
     * 文件上传-获取文件上传地址
     *
     * @param string $contentMd5
     * @param $fileName
     * @param $fileSize
     * @param string $contentType
     * @param bool $convert2Pdf
     * @return \Psr\Http\Message\ResponseInterface
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUploadUrl(string $contentMd5, $fileName, $fileSize, string $contentType = 'application/pdf', bool $convert2Pdf = false)
    {
        $params = [
            'contentMd5' => $contentMd5,
            'fileName' => $fileName,
            'fileSize' => $fileSize,
            'contentType' => $contentType,
            'convert2Pdf' => $convert2Pdf,
        ];
        return $this->httpPostJson('/v1/files/getUploadUrl', $params);
    }

    /**
     * 上传文件
     * @link https://open.esign.cn/doc/opendoc/saas_api/gcu36n#ii1GX
     *
     * @param string $uploadUrl
     * @param string $fileContent
     * @param array $headers ['Content-MD5' => 'string', 'Content-Type' => 'application/pdf']
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload(string $uploadUrl, string $fileContent, array $headers)
    {
        $client = new \GuzzleHttp\Client();
        return json_decode($client->request('put', $uploadUrl, ['body' => $fileContent, 'headers' => $headers])->getBody()->getContents(), true);
    }

    /**
     * @param string $fileId
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function info(string $fileId)
    {
        return $this->httpGet("/v1/files/{$fileId}");
    }

    public function status(string $fileId)
    {
        return $this->httpGet("/v1/files/{$fileId}/status");
    }

    /**
     * 填充内容生成PDF
     *
     * @see https://open.esign.cn/doc/opendoc/saas_api/siipw3
     *
     * @param string $name
     * @param string $templateId
     * @param array $simpleFormFields
     * @param bool $strictCheck
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws BadResponseException
     */
    public function createByTemplate(string $name, string $templateId, array $simpleFormFields, bool $strictCheck = false)
    {
        return $this->httpPostJson('/v1/files/createByTemplate', [
            'name' => $name,
            'templateId' => $templateId,
            'simpleFormFields' => $simpleFormFields,
            'strictCheck' => $strictCheck
        ]);
    }
    
    public function getFileBase64Md5(string $filePath)
    {
        //获取文件MD5的128位二进制数组
        $md5file = md5_file($filePath, true);
        //计算文件的Content-MD5
        return base64_encode($md5file);
    }
}
