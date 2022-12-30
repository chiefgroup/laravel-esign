<?php

namespace QF\LaravelEsign\File;

use GuzzleHttp\Exception\ClientException;
use QF\LaravelEsign\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * 文件上传-获取文件上传地址
     *
     * @param string $fileUrl
     * @param $fileName
     * @param $fileSize
     * @param string $contentType
     * @param bool $convert2Pdf
     * @return \Psr\Http\Message\ResponseInterface
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
     * 文件上传
     * @see https://open.esign.cn/doc/opendoc/saas_api/gcu36n
     * 第一步：获取文件上传地址；第二步：文件流上传
     *
     * @param $fileUrl
     * @param $fileName
     * @param $fileSize
     * @param string $contentType
     * @param bool $convert2Pdf
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadFile($fileUrl, $fileName, $fileSize, string $contentType = 'application/pdf', bool $convert2Pdf = false)
    {
        $contentMd5 = $this->getFileBase64Md5($fileUrl);

        $params = [
            'contentMd5' => $contentMd5,
            'fileName' => $fileName,
            'fileSize' => $fileSize,
            'contentType' => $contentType,
            'convert2Pdf' => $convert2Pdf,
        ];
        $getUploadUrl = $this->httpPostJson('/v1/files/getUploadUrl', $params);

        $fileContent = file_get_contents($fileUrl);

        $headers = [
            'Content-Type:'.$contentType,
            'Content-Md5:'.$contentMd5
        ];
        $parseUrl = parse_url($getUploadUrl['data']['uploadUrl']);
        parse_str($parseUrl['query'], $query);
        try {
            return $this->sendHttpPut($getUploadUrl['data']['uploadUrl'], $fileContent, $headers);
//            $this->httpPutFile($parseUrl['scheme'].  '://'.$parseUrl['host'] . $parseUrl['path'], $query, $fileContent, $headers);
        } catch (ClientException $exception) {
            var_dump($exception->getMessage(), $exception->getCode());
        }
        return 1;
    }

    public function sendHttpPut($uploadUrls, $fileContent, $headers)
    {
        $status = '';
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $uploadUrls);
        curl_setopt($curl_handle, CURLOPT_FILETIME, true);
        curl_setopt($curl_handle, CURLOPT_FRESH_CONNECT, false);
        curl_setopt($curl_handle, CURLOPT_HEADER, true); // 输出HTTP头 true
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 5184000);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'PUT');

        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $fileContent);
        $result = curl_exec($curl_handle);
        $status = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);

        if ($result === false) {
            $status = curl_errno($curl_handle);
            $result = 'put file to oss - curl error :'.curl_error($curl_handle);
        }
        curl_close($curl_handle);
        return $status;
    }

    public function getFileBase64Md5(string $filePath)
    {
        //获取文件MD5的128位二进制数组
        $md5file = md5_file($filePath, true);
        //计算文件的Content-MD5
        return base64_encode($md5file);
    }
}
