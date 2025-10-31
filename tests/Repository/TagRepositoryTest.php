<?php

namespace JiguangPushBundle\Tests\Repository;

use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Device;
use JiguangPushBundle\Entity\Tag;
use JiguangPushBundle\Repository\AccountRepository;
use JiguangPushBundle\Repository\DeviceRepository;
use JiguangPushBundle\Repository\TagRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(TagRepository::class)]
#[RunTestsInSeparateProcesses]
final class TagRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 无需特殊设置
    }

    private function createTestAccount(): Account
    {
        $account = new Account();
        $account->setTitle('Test Account ' . uniqid());
        $account->setAppKey('test_app_key_' . uniqid());
        $account->setMasterSecret('test_master_secret');

        $repository = self::getService(AccountRepository::class);
        $repository->save($account);

        return $account;
    }

    private function createTestDevice(Account $account): Device
    {
        $device = new Device();
        $device->setAccount($account);
        $device->setRegistrationId('test_reg_id_' . uniqid());

        $repository = self::getService(DeviceRepository::class);
        $repository->save($device);

        return $device;
    }

    /**
     * @param array{value?: string, devices?: array<int, Device>} $options
     */
    private function createTestTag(Account $account, array $options = []): Tag
    {
        $tag = new Tag();
        $tag->setAccount($account);
        $tag->setValue($options['value'] ?? 'test_tag_' . uniqid());

        if (isset($options['devices'])) {
            foreach ($options['devices'] as $device) {
                $tag->addDevice($device);
            }
        }

        return $tag;
    }

    public function testTagEntity(): void
    {
        $tag = new Tag();
        $tag->setValue('test_tag');

        $this->assertSame('test_tag', $tag->getValue());
    }

    public function testSave(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();
        $tag = $this->createTestTag($account);

        $repository->save($tag);

        $this->assertGreaterThan(0, $tag->getId());
    }

    public function testSaveWithDevices(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();
        $device1 = $this->createTestDevice($account);
        $device2 = $this->createTestDevice($account);

        $tag = $this->createTestTag($account, ['devices' => [$device1, $device2]]);
        $repository->save($tag);

        $this->assertGreaterThan(0, $tag->getId());
        $this->assertCount(2, $tag->getDevices());
        $this->assertTrue($tag->getDevices()->contains($device1));
        $this->assertTrue($tag->getDevices()->contains($device2));
    }

    public function testRemove(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();
        $tag = $this->createTestTag($account);

        $repository->save($tag);
        $id = $tag->getId();

        $repository->remove($tag);

        $foundTag = $repository->find($id);
        $this->assertNull($foundTag);
    }

    public function testFindByWithAccountCriteriaShouldReturnMatchingTags(): void
    {
        $repository = self::getService(TagRepository::class);
        $account1 = $this->createTestAccount();
        $account2 = $this->createTestAccount();

        $tag1 = $this->createTestTag($account1);
        $tag2 = $this->createTestTag($account2);
        $repository->save($tag1);
        $repository->save($tag2);

        $tags = $repository->findBy(['account' => $account1]);
        $this->assertIsArray($tags);
        $this->assertCount(1, $tags);
        $this->assertInstanceOf(Tag::class, $tags[0]);
        $tagAccount = $tags[0]->getAccount();
        $this->assertNotNull($tagAccount);
        $this->assertSame($account1->getId(), $tagAccount->getId());
    }

    public function testCountWithAccountCriteriaShouldReturnCorrectNumber(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();

        $tag1 = $this->createTestTag($account);
        $tag2 = $this->createTestTag($account);
        $repository->save($tag1);
        $repository->save($tag2);

        $count = $repository->count(['account' => $account]);
        $this->assertSame(2, $count);
    }

    public function testFindByWithMultipleCriteria(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();
        $uniqueValue = 'multi_criteria_' . uniqid();

        $tag = $this->createTestTag($account, ['value' => $uniqueValue]);
        $repository->save($tag);

        $tags = $repository->findBy([
            'account' => $account,
            'value' => $uniqueValue,
        ]);

        $this->assertCount(1, $tags);
        $this->assertInstanceOf(Tag::class, $tags[0]);
        $tagAccount = $tags[0]->getAccount();
        $this->assertNotNull($tagAccount);
        $this->assertSame($account->getId(), $tagAccount->getId());
        $this->assertSame($uniqueValue, $tags[0]->getValue());
    }

    public function testTagWithDevicesRelationship(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();
        $device1 = $this->createTestDevice($account);
        $device2 = $this->createTestDevice($account);

        $tag = $this->createTestTag($account, ['devices' => [$device1, $device2]]);
        $repository->save($tag);

        $foundTag = $repository->find($tag->getId());
        $this->assertInstanceOf(Tag::class, $foundTag);
        $this->assertCount(2, $foundTag->getDevices());
        $this->assertTrue($foundTag->getDevices()->contains($device1));
        $this->assertTrue($foundTag->getDevices()->contains($device2));
    }

    public function testRemoveTagWithDevices(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();
        $device = $this->createTestDevice($account);

        $tag = $this->createTestTag($account, ['devices' => [$device]]);
        $repository->save($tag);
        $tagId = $tag->getId();

        $repository->remove($tag);

        $foundTag = $repository->find($tagId);
        $this->assertNull($foundTag);

        // Device应该仍然存在
        $deviceRepository = self::getService(DeviceRepository::class);
        $foundDevice = $deviceRepository->find($device->getId());
        $this->assertInstanceOf(Device::class, $foundDevice);
    }

    public function testAddAndRemoveDeviceFromTag(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();
        $device1 = $this->createTestDevice($account);
        $device2 = $this->createTestDevice($account);

        $tag = $this->createTestTag($account);
        $repository->save($tag);

        // 添加设备
        $tag->addDevice($device1);
        $tag->addDevice($device2);
        $repository->save($tag);

        $this->assertCount(2, $tag->getDevices());
        $this->assertTrue($tag->getDevices()->contains($device1));
        $this->assertTrue($tag->getDevices()->contains($device2));

        // 移除设备
        $tag->removeDevice($device1);
        $repository->save($tag);

        $this->assertCount(1, $tag->getDevices());
        $this->assertFalse($tag->getDevices()->contains($device1));
        $this->assertTrue($tag->getDevices()->contains($device2));
    }

    public function testStringRepresentation(): void
    {
        $account = $this->createTestAccount();

        // 测试有value的情况
        $tagWithValue = $this->createTestTag($account, ['value' => 'test_value']);
        $this->assertSame('test_value', (string) $tagWithValue);

        // 测试有ID的情况
        $repository = self::getService(TagRepository::class);
        $tagWithId = $this->createTestTag($account);
        $repository->save($tagWithId);
        $expectedString = $tagWithId->getValue() ?? 'Tag #' . $tagWithId->getId();
        $this->assertSame($expectedString, (string) $tagWithId);
    }

    public function testUniqueConstraintOnAccountAndValue(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();
        $tagValue = 'duplicate_value_' . uniqid();

        // 创建第一个标签
        $tag1 = $this->createTestTag($account, ['value' => $tagValue]);
        $repository->save($tag1);
        $this->assertGreaterThan(0, $tag1->getId());

        // 尝试创建具有相同account和value的第二个标签应该失败或被处理
        $tag2 = $this->createTestTag($account, ['value' => $tagValue]);

        // 这里我们期望有唯一约束，但在测试中我们只验证能保存成功
        // 实际的唯一性约束由数据库层面处理
        $this->expectException(\Exception::class);
        $repository->save($tag2);
    }

    public function testDifferentAccountsCanHaveSameTagValue(): void
    {
        $repository = self::getService(TagRepository::class);
        $account1 = $this->createTestAccount();
        $account2 = $this->createTestAccount();
        $tagValue = 'same_value_' . uniqid();

        $tag1 = $this->createTestTag($account1, ['value' => $tagValue]);
        $tag2 = $this->createTestTag($account2, ['value' => $tagValue]);

        $repository->save($tag1);
        $repository->save($tag2);

        $this->assertGreaterThan(0, $tag1->getId());
        $this->assertGreaterThan(0, $tag2->getId());
        $this->assertNotSame($tag1->getId(), $tag2->getId());
        $this->assertSame($tagValue, $tag1->getValue());
        $this->assertSame($tagValue, $tag2->getValue());
    }

    public function testFindByAccountAndValue(): void
    {
        $repository = self::getService(TagRepository::class);
        $account1 = $this->createTestAccount();
        $account2 = $this->createTestAccount();
        $tagValue = 'search_value_' . uniqid();

        $tag1 = $this->createTestTag($account1, ['value' => $tagValue]);
        $tag2 = $this->createTestTag($account2, ['value' => $tagValue]);
        $tag3 = $this->createTestTag($account1, ['value' => 'different_value']);

        $repository->save($tag1);
        $repository->save($tag2);
        $repository->save($tag3);

        $tags = $repository->findBy([
            'account' => $account1,
            'value' => $tagValue,
        ]);

        $this->assertCount(1, $tags);
        $this->assertInstanceOf(Tag::class, $tags[0]);
        $tagAccount = $tags[0]->getAccount();
        $this->assertNotNull($tagAccount);
        $this->assertSame($account1->getId(), $tagAccount->getId());
        $this->assertSame($tagValue, $tags[0]->getValue());
    }

    public function testDeviceTagBidirectionalRelationship(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();
        $device = $this->createTestDevice($account);

        $tag = $this->createTestTag($account);
        $repository->save($tag);

        // 从标签侧添加设备
        $tag->addDevice($device);
        $repository->save($tag);

        // 验证双向关系
        $this->assertTrue($tag->getDevices()->contains($device));
        $this->assertTrue($device->getTags()->contains($tag));

        // 从标签侧移除设备
        $tag->removeDevice($device);
        $repository->save($tag);

        // 验证双向关系的移除
        $this->assertFalse($tag->getDevices()->contains($device));
        $this->assertFalse($device->getTags()->contains($tag));
    }

    public function testFindOneByWithOrderByShouldWork(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();

        $tag1 = $this->createTestTag($account, ['value' => 'aaa_tag']);
        $repository->save($tag1);

        $tag2 = $this->createTestTag($account, ['value' => 'zzz_tag']);
        $repository->save($tag2);

        $foundTag = $repository->findOneBy(['account' => $account], ['value' => 'ASC']);
        $this->assertInstanceOf(Tag::class, $foundTag);
        $this->assertSame('aaa_tag', $foundTag->getValue());

        $foundTag = $repository->findOneBy(['account' => $account], ['value' => 'DESC']);
        $this->assertInstanceOf(Tag::class, $foundTag);
        $this->assertSame('zzz_tag', $foundTag->getValue());
    }

    public function testFindByWithAccountAssociation(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();

        $tag = $this->createTestTag($account);
        $repository->save($tag);

        $tags = $repository->findBy(['account' => $account]);
        $this->assertIsArray($tags);
        $this->assertNotEmpty($tags);
        $this->assertInstanceOf(Tag::class, $tags[0]);
        $tagAccount = $tags[0]->getAccount();
        $this->assertNotNull($tagAccount);
        $this->assertSame($account->getId(), $tagAccount->getId());
    }

    public function testCountWithAccountAssociation(): void
    {
        $repository = self::getService(TagRepository::class);
        $account = $this->createTestAccount();

        $initialCount = $repository->count(['account' => $account]);

        $tag = $this->createTestTag($account);
        $repository->save($tag);

        $newCount = $repository->count(['account' => $account]);
        $this->assertSame($initialCount + 1, $newCount);
    }

    protected function createNewEntity(): Tag
    {
        $account = new Account();
        $account->setTitle('Test Account ' . uniqid());
        $account->setAppKey('test_app_key_' . uniqid());
        $account->setMasterSecret('test_master_secret_' . uniqid());

        $tag = new Tag();
        $tag->setAccount($account);
        $tag->setValue('test_tag_' . uniqid());

        return $tag;
    }

    protected function getRepository(): TagRepository
    {
        return self::getService(TagRepository::class);
    }
}
