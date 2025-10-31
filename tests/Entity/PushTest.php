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
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(Push::class)]
final class PushTest extends AbstractEntityTestCase
{
    protected function createEntity(): Push
    {
        return new Push();
    }

    /** @return iterable<string, array{string, mixed}> */
    public static function propertiesProvider(): iterable
    {
        yield 'platform' => ['platform', PlatformEnum::ALL];
        yield 'cid' => ['cid', 'test-cid-12345'];
        yield 'msgId' => ['msgId', 'test-msg-id-12345'];
        yield 'createTime' => ['createTime', new \DateTimeImmutable()];
        yield 'updateTime' => ['updateTime', new \DateTimeImmutable()];
    }

    public function testGetId(): void
    {
        // 在未设置ID的情况下，应该返回初始值
        $entity = $this->createEntity();
        $this->assertNotNull($entity->getId());
    }

    public function testGetSetAccount(): void
    {
        $entity = $this->createEntity();
        $account = new Account();
        $account->setTitle('测试账号');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $entity->setAccount($account);
        $this->assertSame($account, $entity->getAccount());
    }

    public function testGetSetAudience(): void
    {
        $entity = $this->createEntity();
        $audience = new Audience();
        $audience->setAll(true);

        $entity->setAudience($audience);
        $this->assertSame($audience, $entity->getAudience());
    }

    public function testGetSetNotification(): void
    {
        $entity = $this->createEntity();
        $notification = new Notification();
        $notification->setAlert('测试通知');

        $entity->setNotification($notification);
        $this->assertSame($notification, $entity->getNotification());

        $entity->setNotification(null);
        $this->assertNull($entity->getNotification());
    }

    public function testGetSetMessage(): void
    {
        $entity = $this->createEntity();
        $message = new Message();
        $message->setMsgContent('测试消息内容');

        $entity->setMessage($message);
        $this->assertSame($message, $entity->getMessage());

        $entity->setMessage(null);
        $this->assertNull($entity->getMessage());
    }

    public function testGetSetOptions(): void
    {
        $entity = $this->createEntity();
        $options = new Options();

        $entity->setOptions($options);
        $this->assertSame($options, $entity->getOptions());

        $entity->setOptions(null);
        $this->assertNull($entity->getOptions());
    }

    public function testGetSetCallback(): void
    {
        $entity = $this->createEntity();
        $callback = new Callback();

        $entity->setCallback($callback);
        $this->assertSame($callback, $entity->getCallback());

        $entity->setCallback(null);
        $this->assertNull($entity->getCallback());
    }

    public function testToArrayWithAllFields(): void
    {
        $entity = $this->createEntity();

        // 设置必须字段
        $platform = PlatformEnum::ALL;
        $entity->setPlatform($platform);

        // 设置目标受众
        $audience = new Audience();
        $audience->setAll(true);
        $entity->setAudience($audience);

        // 设置通知内容
        $notification = new Notification();
        $notification->setAlert('测试通知');
        $entity->setNotification($notification);

        // 设置消息内容
        $message = new Message();
        $message->setMsgContent('测试消息内容');
        $entity->setMessage($message);

        // 设置选项
        $options = new Options();
        $entity->setOptions($options);

        // 设置回调
        $callback = new Callback();
        $callback->setUrl('https://example.com/callback');
        $entity->setCallback($callback);

        // 转换为数组
        $data = $entity->toArray();

        // 验证基本结构
        // 验证平台
        $this->assertArrayHasKey('platform', $data);
        $this->assertSame($platform->value, $data['platform']);

        // 验证受众 - 注意audience在toArray()方法中可能已经被转换为字符串"all"
        $this->assertArrayHasKey('audience', $data);
        $this->assertSame('all', $data['audience']);

        // 验证通知
        $this->assertArrayHasKey('notification', $data);
        $this->assertIsArray($data['notification'], '通知结构必须为数组');
        $this->assertArrayHasKey('alert', $data['notification']);
        $this->assertSame('测试通知', $data['notification']['alert']);

        // 验证消息
        $this->assertArrayHasKey('message', $data);
        $this->assertIsArray($data['message'], '消息体必须为数组');
        $this->assertArrayHasKey('msg_content', $data['message']);
        $this->assertSame('测试消息内容', $data['message']['msg_content']);

        // 验证回调
        if (isset($data['callback'])) {
            $this->assertIsArray($data['callback'], '回调结构必须为数组');
            $this->assertArrayHasKey('url', $data['callback']);
            $this->assertSame('https://example.com/callback', $data['callback']['url']);
        }
    }

    public function testToArrayWithMinimalFields(): void
    {
        $entity = $this->createEntity();

        // 只设置必须的字段
        $platform = PlatformEnum::ALL;
        $entity->setPlatform($platform);

        $audience = new Audience();
        $audience->setAll(true);
        $entity->setAudience($audience);

        // 转换为数组
        $data = $entity->toArray();

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
