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
#[CoversClass(DeviceRepository::class)]
#[RunTestsInSeparateProcesses]
final class DeviceRepositoryTest extends AbstractRepositoryTestCase
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

    private function createTestTag(Account $account, ?string $value = null): Tag
    {
        $tag = new Tag();
        $tag->setAccount($account);
        $tag->setValue($value ?? 'test_tag_' . uniqid());

        $repository = self::getService(TagRepository::class);
        $repository->save($tag);

        return $tag;
    }

    /**
     * @param array{registrationId?: string, alias?: string|null, mobile?: string|null, tags?: array<Tag>} $options
     */
    private function createTestDevice(Account $account, array $options = []): Device
    {
        $device = new Device();
        $device->setAccount($account);
        $device->setRegistrationId($options['registrationId'] ?? 'test_reg_id_' . uniqid());

        if (isset($options['alias'])) {
            $device->setAlias($options['alias']);
        }

        if (isset($options['mobile'])) {
            $device->setMobile($options['mobile']);
        }

        if (isset($options['tags'])) {
            foreach ($options['tags'] as $tag) {
                $device->addTag($tag);
            }
        }

        return $device;
    }

    public function testDeviceEntity(): void
    {
        $device = new Device();
        $device->setRegistrationId('test_registration_id');

        $this->assertSame('test_registration_id', $device->getRegistrationId());
    }

    public function testSave(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();
        $device = $this->createTestDevice($account);

        $repository->save($device);

        $this->assertGreaterThan(0, $device->getId());
    }

    public function testSaveWithTags(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();
        $tag1 = $this->createTestTag($account, 'tag1_' . uniqid());
        $tag2 = $this->createTestTag($account, 'tag2_' . uniqid());

        $device = $this->createTestDevice($account, ['tags' => [$tag1, $tag2]]);
        $repository->save($device);

        $this->assertGreaterThan(0, $device->getId());
        $this->assertCount(2, $device->getTags());
        $this->assertTrue($device->getTags()->contains($tag1));
        $this->assertTrue($device->getTags()->contains($tag2));
    }

    public function testSaveWithNullableFields(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();
        $device = $this->createTestDevice($account, [
            'alias' => 'test_alias_' . uniqid(),
            'mobile' => '13800138000',
        ]);

        $repository->save($device);

        $this->assertGreaterThan(0, $device->getId());
        $alias = $device->getAlias();
        $this->assertNotNull($alias);
        $this->assertStringContainsString('test_alias_', $alias);
        $this->assertSame('13800138000', $device->getMobile());
    }

    public function testRemove(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();
        $device = $this->createTestDevice($account);

        $repository->save($device);
        $id = $device->getId();

        $repository->remove($device);

        $foundDevice = $repository->find($id);
        $this->assertNull($foundDevice);
    }

    public function testFindByWithAccountCriteriaShouldReturnMatchingDevices(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account1 = $this->createTestAccount();
        $account2 = $this->createTestAccount();

        $device1 = $this->createTestDevice($account1);
        $device2 = $this->createTestDevice($account2);
        $repository->save($device1);
        $repository->save($device2);

        $devices = $repository->findBy(['account' => $account1]);
        $this->assertIsArray($devices);
        $this->assertCount(1, $devices);
        $this->assertInstanceOf(Device::class, $devices[0]);
        $deviceAccount = $devices[0]->getAccount();
        $this->assertNotNull($deviceAccount);
        $this->assertSame($account1->getId(), $deviceAccount->getId());
    }

    public function testCountWithAccountCriteriaShouldReturnCorrectNumber(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();

        $device1 = $this->createTestDevice($account);
        $device2 = $this->createTestDevice($account);
        $repository->save($device1);
        $repository->save($device2);

        $count = $repository->count(['account' => $account]);
        $this->assertSame(2, $count);
    }

    public function testFindByWithNullAliasShouldWork(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();

        $device = $this->createTestDevice($account); // alias默认为null
        $repository->save($device);

        $devices = $repository->findBy(['alias' => null]);
        $this->assertIsArray($devices);
        $this->assertGreaterThanOrEqual(1, count($devices));

        foreach ($devices as $foundDevice) {
            $this->assertInstanceOf(Device::class, $foundDevice);
            $this->assertNull($foundDevice->getAlias());
        }
    }

    public function testFindByWithNullMobileShouldWork(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();

        $device = $this->createTestDevice($account); // mobile默认为null
        $repository->save($device);

        $devices = $repository->findBy(['mobile' => null]);
        $this->assertIsArray($devices);
        $this->assertGreaterThanOrEqual(1, count($devices));

        foreach ($devices as $foundDevice) {
            $this->assertInstanceOf(Device::class, $foundDevice);
            $this->assertNull($foundDevice->getMobile());
        }
    }

    public function testCountWithNullFieldShouldWork(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();

        $device = $this->createTestDevice($account); // alias默认为null
        $repository->save($device);

        $count = $repository->count(['alias' => null]);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testDeviceWithTagsRelationship(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();
        $tag1 = $this->createTestTag($account, 'premium');
        $tag2 = $this->createTestTag($account, 'vip');

        $device = $this->createTestDevice($account, ['tags' => [$tag1, $tag2]]);
        $repository->save($device);

        $foundDevice = $repository->find($device->getId());
        $this->assertInstanceOf(Device::class, $foundDevice);
        $this->assertCount(2, $foundDevice->getTags());

        $tagValues = [];
        foreach ($foundDevice->getTags() as $tag) {
            $tagValues[] = $tag->getValue();
        }

        $this->assertContains('premium', $tagValues);
        $this->assertContains('vip', $tagValues);
    }

    public function testRemoveDeviceWithTags(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();
        $tag = $this->createTestTag($account);

        $device = $this->createTestDevice($account, ['tags' => [$tag]]);
        $repository->save($device);
        $deviceId = $device->getId();

        $repository->remove($device);

        $foundDevice = $repository->find($deviceId);
        $this->assertNull($foundDevice);

        // 标签应该仍然存在
        $tagRepository = self::getService(TagRepository::class);
        $foundTag = $tagRepository->find($tag->getId());
        $this->assertInstanceOf(Tag::class, $foundTag);
    }

    public function testFindByWithMultipleCriteria(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();
        $uniqueAlias = 'multi_criteria_' . uniqid();
        $uniqueMobile = '13900139000';

        $device = $this->createTestDevice($account, [
            'alias' => $uniqueAlias,
            'mobile' => $uniqueMobile,
        ]);
        $repository->save($device);

        $devices = $repository->findBy([
            'alias' => $uniqueAlias,
            'mobile' => $uniqueMobile,
        ]);

        $this->assertCount(1, $devices);
        $this->assertInstanceOf(Device::class, $devices[0]);
        $this->assertSame($uniqueAlias, $devices[0]->getAlias());
        $this->assertSame($uniqueMobile, $devices[0]->getMobile());
    }

    public function testStringRepresentation(): void
    {
        $account = $this->createTestAccount();

        // 测试有alias的情况
        $deviceWithAlias = $this->createTestDevice($account, ['alias' => 'test_alias']);
        $this->assertSame('test_alias', (string) $deviceWithAlias);

        // 测试没有alias但有registrationId的情况
        $deviceWithRegId = $this->createTestDevice($account, ['registrationId' => 'test_reg_123']);
        $this->assertSame('test_reg_123', (string) $deviceWithRegId);

        // 测试有ID的情况
        $repository = self::getService(DeviceRepository::class);
        $deviceWithId = $this->createTestDevice($account);
        $repository->save($deviceWithId);
        $expectedString = $deviceWithId->getAlias() ?? $deviceWithId->getRegistrationId() ?? 'Device #' . $deviceWithId->getId();
        $this->assertSame($expectedString, (string) $deviceWithId);
    }

    public function testFindOneByWithOrderByShouldWork(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();

        $device1 = $this->createTestDevice($account, ['alias' => 'Device A']);
        $repository->save($device1);

        $device2 = $this->createTestDevice($account, ['alias' => 'Device Z']);
        $repository->save($device2);

        $foundDevice = $repository->findOneBy(['account' => $account], ['alias' => 'ASC']);
        $this->assertInstanceOf(Device::class, $foundDevice);
        $this->assertSame('Device A', $foundDevice->getAlias());

        $foundDevice = $repository->findOneBy(['account' => $account], ['alias' => 'DESC']);
        $this->assertInstanceOf(Device::class, $foundDevice);
        $this->assertSame('Device Z', $foundDevice->getAlias());
    }

    public function testFindByWithAccountAssociation(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();

        $device = $this->createTestDevice($account);
        $repository->save($device);

        $devices = $repository->findBy(['account' => $account]);
        $this->assertIsArray($devices);
        $this->assertNotEmpty($devices);
        $this->assertInstanceOf(Device::class, $devices[0]);
        $deviceAccount = $devices[0]->getAccount();
        $this->assertNotNull($deviceAccount);
        $this->assertSame($account->getId(), $deviceAccount->getId());
    }

    public function testCountWithAccountAssociation(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();

        $initialCount = $repository->count(['account' => $account]);

        $device = $this->createTestDevice($account);
        $repository->save($device);

        $newCount = $repository->count(['account' => $account]);
        $this->assertSame($initialCount + 1, $newCount);
    }

    public function testCountWithNullAliasField(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();

        $device = $this->createTestDevice($account); // alias默认为null
        $repository->save($device);

        $count = $repository->count(['alias' => null]);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testCountWithNullMobileField(): void
    {
        $repository = self::getService(DeviceRepository::class);
        $account = $this->createTestAccount();

        $device = $this->createTestDevice($account); // mobile默认为null
        $repository->save($device);

        $count = $repository->count(['mobile' => null]);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    protected function createNewEntity(): Device
    {
        $account = $this->createTestAccount();

        return $this->createTestDevice($account);
    }

    protected function getRepository(): DeviceRepository
    {
        return self::getService(DeviceRepository::class);
    }

    protected function getTestEntityClass(): string
    {
        return Device::class;
    }
}
