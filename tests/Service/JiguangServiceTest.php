<?php

namespace JiguangPushBundle\Tests\Service;

use HttpClientBundle\Request\RequestInterface;
use HttpClientBundle\Service\SmartHttpClient;
use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Request\PushRequest;
use JiguangPushBundle\Service\JiguangService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(JiguangService::class)]
#[RunTestsInSeparateProcesses]
final class JiguangServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
    }

    private function createJiguangService(): TestableJiguangServiceInterface
    {
        $logger = $this->createMock(LoggerInterface::class);
        $httpClient = $this->createMock(SmartHttpClient::class);
        $lockFactory = $this->createMock(LockFactory::class);
        $cache = $this->createMock(CacheInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $asyncInsertService = $this->createMock(AsyncInsertService::class);

        return new class($logger, $httpClient, $lockFactory, $cache, $eventDispatcher, $asyncInsertService) extends JiguangService implements TestableJiguangServiceInterface {
            public function testGetRequestUrl(RequestInterface $request): string
            {
                return $this->getRequestUrl($request);
            }

            public function testGetRequestMethod(RequestInterface $request): string
            {
                return $this->getRequestMethod($request);
            }

            /** @return array<string, mixed>|null */
            public function testGetRequestOptions(RequestInterface $request): ?array
            {
                $options = $this->getRequestOptions($request);
                if ($options === null) {
                    return null;
                }

                // 确保返回符合 array<string, mixed> 类型
                $result = [];
                foreach ($options as $key => $value) {
                    if (is_string($key)) {
                        $result[$key] = $value;
                    }
                }
                return $result;
            }

            /** @return array<string, mixed> */
            public function testFormatResponse(RequestInterface $request, ResponseInterface $response): array
            {
                return $this->formatResponse($request, $response);
            }
        };
    }

    /**
     * 测试获取请求URL
     */
    public function testGetRequestUrl(): void
    {
        $service = $this->createJiguangService();

        /*
         * 使用具体类 PushRequest 而不是接口的原因：
         * 1. PushRequest 是推送请求的核心实现，测试需要验证具体的推送逻辑
         * 2. JiguangService 与 PushRequest 有紧密的业务耦合，测试这种耦合是必要的
         * 3. 这是一个集成测试场景，需要验证具体实现的行为而不是抽象接口
         */
        $request = $this->createMock(PushRequest::class);
        $request->method('getRequestPath')
            ->willReturn('https://api.jpush.cn/v3/push')
        ;

        $url = $service->testGetRequestUrl($request);
        $this->assertSame('https://api.jpush.cn/v3/push', $url);
    }

    /**
     * 测试获取请求方法
     */
    public function testGetRequestMethod(): void
    {
        $service = $this->createJiguangService();

        /*
         * 使用具体类 PushRequest 而不是接口的原因：
         * 1. PushRequest 是推送请求的核心实现，测试需要验证具体的推送逻辑
         * 2. JiguangService 与 PushRequest 有紧密的业务耦合，测试这种耦合是必要的
         * 3. 这是一个集成测试场景，需要验证具体实现的行为而不是抽象接口
         */
        $request = $this->createMock(PushRequest::class);
        $request->method('getRequestMethod')
            ->willReturn('POST')
        ;

        $requestMethod = $service->testGetRequestMethod($request);
        $this->assertSame('POST', $requestMethod);
    }

    /**
     * 测试默认请求方法
     */
    public function testGetRequestMethodDefault(): void
    {
        $service = $this->createJiguangService();

        /*
         * 使用具体类 PushRequest 而不是接口的原因：
         * 1. PushRequest 是推送请求的核心实现，测试需要验证具体的推送逻辑
         * 2. JiguangService 与 PushRequest 有紧密的业务耦合，测试这种耦合是必要的
         * 3. 这是一个集成测试场景，需要验证具体实现的行为而不是抽象接口
         */
        $request = $this->createMock(PushRequest::class);
        $request->method('getRequestMethod')
            ->willReturn(null)
        ;

        $requestMethod = $service->testGetRequestMethod($request);
        $this->assertSame('POST', $requestMethod);
    }

    /**
     * 测试获取请求选项，包括授权头
     */
    public function testGetRequestOptionsWithAuth(): void
    {
        $service = $this->createJiguangService();

        // 创建账号
        $account = new Account();
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        /*
         * 使用具体类 PushRequest 而不是接口的原因：
         * 1. PushRequest 是推送请求的核心实现，测试需要验证具体的推送逻辑
         * 2. JiguangService 与 PushRequest 有紧密的业务耦合，测试这种耦合是必要的
         * 3. 这是一个集成测试场景，需要验证具体实现的行为而不是抽象接口
         */
        $request = $this->createMock(PushRequest::class);
        $request->method('getRequestOptions')
            ->willReturn(['json' => ['platform' => 'all']])
        ;
        $request->method('getAccount')
            ->willReturn($account)
        ;

        $options = $service->testGetRequestOptions($request);
        $this->assertNotNull($options);
        $this->assertArrayHasKey('headers', $options);
        $this->assertIsArray($options['headers']);
        $this->assertArrayHasKey('Authorization', $options['headers']);

        // 验证授权头格式是否正确
        $expectedAuth = 'Basic ' . base64_encode('test_app_key:test_master_secret');
        $this->assertSame($expectedAuth, $options['headers']['Authorization']);

        // 验证原始选项是否保留
        $this->assertArrayHasKey('json', $options);
        $this->assertSame(['platform' => 'all'], $options['json']);
    }

    /**
     * 测试格式化响应
     */
    public function testFormatResponse(): void
    {
        $service = $this->createJiguangService();

        $request = $this->createMock(RequestInterface::class);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')
            ->willReturn('{"msg_id":"12345","sendno":"54321"}')
        ;

        $result = $service->testFormatResponse($request, $response);
        $this->assertArrayHasKey('msg_id', $result);
        $this->assertArrayHasKey('sendno', $result);
        $this->assertSame('12345', $result['msg_id']);
        $this->assertSame('54321', $result['sendno']);
    }
}
