<?php

namespace JiguangPushBundle\Tests\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Embedded\Audience;
use JiguangPushBundle\Entity\Push;
use JiguangPushBundle\Enum\PlatformEnum;
use JiguangPushBundle\EventSubscriber\PushListener;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(PushListener::class)]
#[RunTestsInSeparateProcesses]
final class PushListenerTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 无需特殊设置
    }

    public function testPushListenerConstruction(): void
    {
        // 在集成测试中，应该从容器获取服务而不是直接实例化
        $listener = self::getService(PushListener::class);

        $this->assertInstanceOf(PushListener::class, $listener);
    }

    public function testPostPersist(): void
    {
        // 由于 PushListener 是实体事件监听器，并且其依赖是只读属性，
        // 我们不能简单地模拟服务。相反，我们将测试一个已经设置了 msg_id 的 Push，
        // 以验证监听器不会重复处理

        // 创建测试数据
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_key');
        $account->setMasterSecret('test_secret');
        $account->setValid(true);

        $entityManager = self::getService(EntityManagerInterface::class);
        $entityManager->persist($account);
        $entityManager->flush();

        // 创建 Push 实体，设置所有必需的字段
        $audience = new Audience();
        $audience->setAll(true);

        $push = new Push();
        $push->setAccount($account);
        $push->setPlatform(PlatformEnum::ALL);
        $push->setAudience($audience);

        // 先设置一个 msg_id，这应该防止监听器调用 JiguangService
        $push->setMsgId('pre_existing_msg_id');

        // 获取监听器实例
        $listener = self::getService(PushListener::class);

        // 直接调用 postPersist 方法
        $listener->postPersist($push);

        // 验证 msg_id 没有被改变
        $this->assertSame('pre_existing_msg_id', $push->getMsgId());
    }
}
