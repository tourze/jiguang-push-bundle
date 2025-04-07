<?php

namespace JiguangPushBundle\Service;

use HttpClientBundle\Client\ApiClient;
use HttpClientBundle\Request\RequestInterface;
use JiguangPushBundle\Request\WithAccountRequest;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yiisoft\Json\Json;

/**
 * 极光推送服务
 *
 * @see https://docs.jiguang.cn/jpush/server/push/server_overview#%E9%89%B4%E6%9D%83%E6%96%B9%E5%BC%8F
 */
class JiguangService extends ApiClient
{
    protected function getRequestUrl(RequestInterface $request): string
    {
        return $request->getRequestPath();
    }

    protected function getRequestMethod(RequestInterface $request): string
    {
        return $request->getRequestMethod() ?: 'POST';
    }

    protected function getRequestOptions(RequestInterface $request): ?array
    {
        $options = $request->getRequestOptions();
        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }

        if ($request instanceof WithAccountRequest) {
            $options['headers']['Authorization'] = 'Basic ' . base64_encode($request->getAccount()->getAppKey() . ':' . $request->getAccount()->getMasterSecret());
        }

        return $options;
    }

    protected function formatResponse(RequestInterface $request, ResponseInterface $response): array
    {
        return Json::decode($response->getContent());
    }
}
