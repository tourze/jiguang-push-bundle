<?php

namespace JiguangPushBundle\Tests\Service;

use HttpClientBundle\Request\RequestInterface;
use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Request\PushRequest;
use JiguangPushBundle\Service\JiguangService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;

class JiguangServiceTest extends TestCase
{
    private JiguangService $service;

    protected function setUp(): void
    {
        $this->service = $this->getMockBuilder(JiguangService::class)
            ->onlyMethods(['request'])
            ->getMock();
    }

    /**
     * 测试获取请求URL
     */
    public function testGetRequestUrl(): void
    {
        $request = $this->createMock(PushRequest::class);
        $request->method('getRequestPath')
            ->willReturn('https://api.jpush.cn/v3/push');

        $method = new \ReflectionMethod(JiguangService::class, 'getRequestUrl');
        $method->setAccessible(true);
        
        $url = $method->invoke($this->service, $request);
        $this->assertSame('https://api.jpush.cn/v3/push', $url);
    }

    /**
     * 测试获取请求方法
     */
    public function testGetRequestMethod(): void
    {
        $request = $this->createMock(PushRequest::class);
        $request->method('getRequestMethod')
            ->willReturn('POST');

        $method = new \ReflectionMethod(JiguangService::class, 'getRequestMethod');
        $method->setAccessible(true);
        
        $requestMethod = $method->invoke($this->service, $request);
        $this->assertSame('POST', $requestMethod);
    }

    /**
     * 测试默认请求方法
     */
    public function testGetRequestMethodDefault(): void
    {
        $request = $this->createMock(PushRequest::class);
        $request->method('getRequestMethod')
            ->willReturn(null);

        $method = new \ReflectionMethod(JiguangService::class, 'getRequestMethod');
        $method->setAccessible(true);
        
        $requestMethod = $method->invoke($this->service, $request);
        $this->assertSame('POST', $requestMethod);
    }

    /**
     * 测试获取请求选项，包括授权头
     */
    public function testGetRequestOptionsWithAuth(): void
    {
        // 创建账号
        $account = new Account();
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');
        
        // 创建请求
        $request = $this->createMock(PushRequest::class);
        $request->method('getRequestOptions')
            ->willReturn(['json' => ['platform' => 'all']]);
        $request->method('getAccount')
            ->willReturn($account);
        
        $method = new \ReflectionMethod(JiguangService::class, 'getRequestOptions');
        $method->setAccessible(true);
        
        $options = $method->invoke($this->service, $request);
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('headers', $options);
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
        $request = $this->createMock(RequestInterface::class);
        
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')
            ->willReturn('{"msg_id":"12345","sendno":"54321"}');
        
        $method = new \ReflectionMethod(JiguangService::class, 'formatResponse');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->service, $request, $response);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('msg_id', $result);
        $this->assertArrayHasKey('sendno', $result);
        $this->assertSame('12345', $result['msg_id']);
        $this->assertSame('54321', $result['sendno']);
    }

    /**
     * 测试完整的请求流程，但由于无法模拟整个HTTP请求过程，
     * 我们将跳过这个测试，或者使用更高级的集成测试方法。
     */
    public function testFullRequestFlow(): void
    {
        $this->markTestSkipped('此测试需要模拟完整的HTTP请求流程，超出了单元测试范围');
    }
} 