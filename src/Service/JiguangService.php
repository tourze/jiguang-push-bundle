<?php

namespace JiguangPushBundle\Service;

use HttpClientBundle\Client\ApiClient;
use HttpClientBundle\Request\RequestInterface;
use HttpClientBundle\Service\SmartHttpClient;
use JiguangPushBundle\Request\WithAccountRequest;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService;
use Yiisoft\Json\Json;

/**
 * 极光推送服务
 *
 * @see https://docs.jiguang.cn/jpush/server/push/server_overview#%E9%89%B4%E6%9D%83%E6%96%B9%E5%BC%8F
 */
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'jiguang_push')]
class JiguangService extends ApiClient
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SmartHttpClient $httpClient,
        private readonly LockFactory $lockFactory,
        private readonly CacheInterface $cache,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly AsyncInsertService $asyncInsertService,
    ) {
    }

    protected function getLockFactory(): LockFactory
    {
        return $this->lockFactory;
    }

    protected function getHttpClient(): SmartHttpClient
    {
        return $this->httpClient;
    }

    protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    protected function getCache(): CacheInterface
    {
        return $this->cache;
    }

    protected function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    protected function getAsyncInsertService(): AsyncInsertService
    {
        return $this->asyncInsertService;
    }

    protected function getRequestUrl(RequestInterface $request): string
    {
        return $request->getRequestPath();
    }

    protected function getRequestMethod(RequestInterface $request): string
    {
        return $request->getRequestMethod() ?? 'POST';
    }

    protected function getRequestOptions(RequestInterface $request): ?array
    {
        $options = $request->getRequestOptions();
        if (!is_array($options)) {
            $options = [];
        }

        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }

        if ($request instanceof WithAccountRequest) {
            if (!is_array($options['headers'])) {
                $options['headers'] = [];
            }
            $options['headers']['Authorization'] = 'Basic ' . base64_encode($request->getAccount()->getAppKey() . ':' . $request->getAccount()->getMasterSecret());
        }

        return $options;
    }

    /** @return array<string, mixed> */
    protected function formatResponse(RequestInterface $request, ResponseInterface $response): array
    {
        $decoded = Json::decode($response->getContent());
        if (!is_array($decoded)) {
            return [];
        }

        // 确保返回的数组符合 array<string, mixed> 类型
        $result = [];
        foreach ($decoded as $key => $value) {
            if (is_string($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
