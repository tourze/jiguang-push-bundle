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
#[CoversClass(Device::class)]
final class DeviceTest extends AbstractEntityTestCase
{
    protected function createEntity(): Device
    {
        return new Device();
    }

    /** @return iterable<string, array{string, mixed}> */
    public static function propertiesProvider(): iterable
    {
        yield 'registrationId' => ['registrationId', 'test-registration-id-12345'];
        yield 'alias' => ['alias', 'test-alias'];
        yield 'mobile' => ['mobile', '13800138000'];
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

    public function testTagsCollectionInitialization(): void
    {
        $entity = $this->createEntity();
        $tags = $entity->getTags();
        $this->assertInstanceOf(ArrayCollection::class, $tags);
        $this->assertCount(0, $tags);
    }

    public function testAddRemoveTag(): void
    {
        $entity = $this->createEntity();
        $tag = new Tag();
        $tag->setValue('test-tag');

        // 添加标签
        $entity->addTag($tag);
        $this->assertCount(1, $entity->getTags());
        $this->assertTrue($entity->getTags()->contains($tag));

        // 再次添加相同标签不会重复
        $entity->addTag($tag);
        $this->assertCount(1, $entity->getTags());

        // 移除标签
        $entity->removeTag($tag);
        $this->assertCount(0, $entity->getTags());
        $this->assertFalse($entity->getTags()->contains($tag));
    }
}
