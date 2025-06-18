<?php

namespace JiguangPushBundle\Tests\Entity;

use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Embedded\Audience;
use JiguangPushBundle\Entity\Embedded\Callback;
use JiguangPushBundle\Entity\Embedded\Message;
use JiguangPushBundle\Entity\Embedded\Notification;
use JiguangPushBundle\Entity\Embedded\Options;
use JiguangPushBundle\Entity\Push;
use JiguangPushBundle\Enum\PlatformEnum;
use PHPUnit\Framework\TestCase;

class PushTest extends TestCase
{
    private Push $push;
    private Account $account;

    protected function setUp(): void
    {
        $this->push = new Push();
        $this->account = new Account();
        // 不能设置ID，它是自动生成的
        $this->account->setTitle('测试账号');
        $this->account->setAppKey('test_app_key');
        $this->account->setMasterSecret('test_master_secret');
    }

    public function testGetId(): void
    {
        // 在未设置ID的情况下，应该返回初始值
        $this->assertNotNull($this->push->getId());
    }

    public function testGetSetAccount(): void
    {
        $this->push->setAccount($this->account);
        $this->assertSame($this->account, $this->push->getAccount());
    }

    public function testGetSetPlatform(): void
    {
        $platform = PlatformEnum::ALL;
        $this->push->setPlatform($platform);
        $this->assertSame($platform, $this->push->getPlatform());
    }

    public function testGetSetAudience(): void
    {
        $audience = new Audience();
        $audience->setAll(true);
        
        $this->push->setAudience($audience);
        $this->assertSame($audience, $this->push->getAudience());
    }

    public function testGetSetNotification(): void
    {
        $notification = new Notification();
        $notification->setAlert('测试通知');
        
        $this->push->setNotification($notification);
        $this->assertSame($notification, $this->push->getNotification());
        
        $this->push->setNotification(null);
        $this->assertNull($this->push->getNotification());
    }

    public function testGetSetMessage(): void
    {
        $message = new Message();
        $message->setMsgContent('测试消息内容');
        
        $this->push->setMessage($message);
        $this->assertSame($message, $this->push->getMessage());
        
        $this->push->setMessage(null);
        $this->assertNull($this->push->getMessage());
    }

    public function testGetSetOptions(): void
    {
        $options = new Options();
        
        $this->push->setOptions($options);
        $this->assertSame($options, $this->push->getOptions());
        
        $this->push->setOptions(null);
        $this->assertNull($this->push->getOptions());
    }

    public function testGetSetCallback(): void
    {
        $callback = new Callback();
        
        $this->push->setCallback($callback);
        $this->assertSame($callback, $this->push->getCallback());
        
        $this->push->setCallback(null);
        $this->assertNull($this->push->getCallback());
    }

    public function testGetSetCid(): void
    {
        $cid = 'test-cid-12345';
        $this->push->setCid($cid);
        $this->assertSame($cid, $this->push->getCid());
        
        $this->push->setCid(null);
        $this->assertNull($this->push->getCid());
    }

    public function testGetSetMsgId(): void
    {
        $msgId = 'test-msg-id-12345';
        $this->push->setMsgId($msgId);
        $this->assertSame($msgId, $this->push->getMsgId());
        
        $this->push->setMsgId(null);
        $this->assertNull($this->push->getMsgId());
    }

    public function testGetSetCreateTime(): void
    {
        $date = new \DateTime();
        $this->push->setCreateTime($date);
        $this->assertSame($date, $this->push->getCreateTime());
    }

    public function testGetSetUpdateTime(): void
    {
        $date = new \DateTime();
        $this->push->setUpdateTime($date);
        $this->assertSame($date, $this->push->getUpdateTime());
    }

    public function testToArrayWithAllFields(): void
    {
        // 设置必须字段
        $platform = PlatformEnum::ALL;
        $this->push->setPlatform($platform);
        
        // 设置目标受众
        $audience = new Audience();
        $audience->setAll(true);
        $this->push->setAudience($audience);
        
        // 设置通知内容
        $notification = new Notification();
        $notification->setAlert('测试通知');
        $this->push->setNotification($notification);
        
        // 设置消息内容
        $message = new Message();
        $message->setMsgContent('测试消息内容');
        $this->push->setMessage($message);
        
        // 设置选项
        $options = new Options();
        $this->push->setOptions($options);
        
        // 设置回调
        $callback = new Callback();
        $callback->setUrl('https://example.com/callback');
        $this->push->setCallback($callback);
        
        // 转换为数组
        $data = $this->push->toArray();
        
        // 验证基本结构
        // 验证平台
        $this->assertArrayHasKey('platform', $data);
        $this->assertSame($platform->value, $data['platform']);
        
        // 验证受众 - 注意audience在toArray()方法中可能已经被转换为字符串"all"
        $this->assertArrayHasKey('audience', $data);
        $this->assertSame('all', $data['audience']);
        
        // 验证通知
        $this->assertArrayHasKey('notification', $data);
        $this->assertArrayHasKey('alert', $data['notification']);
        $this->assertSame('测试通知', $data['notification']['alert']);
        
        // 验证消息
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('msg_content', $data['message']);
        $this->assertSame('测试消息内容', $data['message']['msg_content']);
        
        // 验证回调
        if (isset($data['callback'])) {
            $this->assertArrayHasKey('url', $data['callback']);
            $this->assertSame('https://example.com/callback', $data['callback']['url']);
        }
    }

    public function testToArrayWithMinimalFields(): void
    {
        // 只设置必须的字段
        $platform = PlatformEnum::ALL;
        $this->push->setPlatform($platform);
        
        $audience = new Audience();
        $audience->setAll(true);
        $this->push->setAudience($audience);
        
        // 转换为数组
        $data = $this->push->toArray();
        
        // 验证基本结构
        // 验证平台
        $this->assertArrayHasKey('platform', $data);
        $this->assertSame($platform->value, $data['platform']);
        
        // 验证受众 - 注意audience在toArray()方法中可能已经被转换为字符串"all"
        $this->assertArrayHasKey('audience', $data);
        $this->assertSame('all', $data['audience']);
        
        // 可选字段应该不存在
        $this->assertArrayNotHasKey('notification', $data);
        $this->assertArrayNotHasKey('message', $data);
    }
} 