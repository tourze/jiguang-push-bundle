<?php

namespace JiguangPushBundle\Tests\Request;

use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Embedded\Audience;
use JiguangPushBundle\Entity\Embedded\Message;
use JiguangPushBundle\Entity\Push;
use JiguangPushBundle\Enum\PlatformEnum;
use JiguangPushBundle\Request\PushRequest;
use PHPUnit\Framework\TestCase;

class PushRequestTest extends TestCase
{
    private PushRequest $request;
    private Account $account;
    private Push $message;

    protected function setUp(): void
    {
        $this->request = new PushRequest();
        
        // 创建账号
        $this->account = new Account();
        // 不能设置ID，它是自动生成的
        $this->account->setAppKey('test_app_key');
        $this->account->setMasterSecret('test_master_secret');
        
        // 创建推送消息
        $this->message = new Push();
        $this->message->setPlatform(PlatformEnum::ALL);
        
        $audience = new Audience();
        $audience->setAll(true);
        $this->message->setAudience($audience);
        
        $msgContent = new Message();
        $msgContent->setMsgContent('测试消息内容');
        $this->message->setMessage($msgContent);
    }

    public function testGetSetMessage(): void
    {
        $this->request->setMessage($this->message);
        $this->assertSame($this->message, $this->request->getMessage());
    }

    public function testGetSetAccount(): void
    {
        $this->request->setAccount($this->account);
        $this->assertSame($this->account, $this->request->getAccount());
    }

    public function testGetRequestPath(): void
    {
        $expectedPath = 'https://api.jpush.cn/v3/push';
        $this->assertSame($expectedPath, $this->request->getRequestPath());
    }

    public function testGetRequestOptions(): void
    {
        $this->request->setMessage($this->message);
        
        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertSame($this->message->toArray(), $options['json']);
    }

    public function testGetRequestOptionsWithComplexMessage(): void
    {
        // 设置更复杂的消息
        $audience = new Audience();
        $audience->setRegistrationId(['reg_id_1', 'reg_id_2']);
        $this->message->setAudience($audience);
        
        $this->request->setMessage($this->message);
        
        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('platform', $json);
        $this->assertArrayHasKey('audience', $json);
        $this->assertArrayHasKey('message', $json);
        
        // 验证受众
        $this->assertIsArray($json['audience']);
        $this->assertArrayHasKey('registration_id', $json['audience']);
        $this->assertSame(['reg_id_1', 'reg_id_2'], $json['audience']['registration_id']);
    }
} 