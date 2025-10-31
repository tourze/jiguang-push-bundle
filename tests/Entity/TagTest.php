<?php

namespace JiguangPushBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Device;
use JiguangPushBundle\Entity\Tag;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(Tag::class)]
final class TagTest extends AbstractEntityTestCase
{
    protected function createEntity(): Tag
    {
        return new Tag();
    }

    /** @return iterable<string, array{string, mixed}> */
    public static function propertiesProvider(): iterable
    {
        yield 'value' => ['value', 'test-tag-value'];
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

    public function testDevicesCollectionInitialization(): void
    {
        $entity = $this->createEntity();
        $devices = $entity->getDevices();
        $this->assertInstanceOf(ArrayCollection::class, $devices);
        $this->assertCount(0, $devices);
    }

    public function testAddRemoveDevice(): void
    {
        $entity = $this->createEntity();
        $device = new Device();
        $device->setRegistrationId('test-registration-id');

        // 添加设备
        $entity->addDevice($device);
        $this->assertCount(1, $entity->getDevices());
        $this->assertTrue($entity->getDevices()->contains($device));

        // 再次添加相同设备不会重复
        $entity->addDevice($device);
        $this->assertCount(1, $entity->getDevices());

        // 移除设备
        $entity->removeDevice($device);
        $this->assertCount(0, $entity->getDevices());
        $this->assertFalse($entity->getDevices()->contains($device));
    }
}
