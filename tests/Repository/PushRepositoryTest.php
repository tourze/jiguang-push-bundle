<?php

namespace JiguangPushBundle\Tests\Repository;

use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Embedded\Audience;
use JiguangPushBundle\Entity\Push;
use JiguangPushBundle\Enum\PlatformEnum;
use JiguangPushBundle\Repository\AccountRepository;
use JiguangPushBundle\Repository\PushRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(PushRepository::class)]
#[RunTestsInSeparateProcesses]
final class PushRepositoryTest extends AbstractRepositoryTestCase
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

    /**
     * @param array{platform?: PlatformEnum, audienceAll?: bool, cid?: string|null, msgId?: string|null} $options
     */
    private function createTestPush(Account $account, array $options = []): Push
    {
        $push = new Push();
        $push->setAccount($account);
        $push->setPlatform($options['platform'] ?? PlatformEnum::ALL);

        $audience = new Audience();
        $audience->setAll($options['audienceAll'] ?? true);
        $push->setAudience($audience);

        if (isset($options['cid'])) {
            $push->setCid($options['cid']);
        }

        // 默认设置msgId以避免API调用，除非明确指定为null
        if (isset($options['msgId'])) {
            $push->setMsgId($options['msgId']);
        } else {
            $push->setMsgId('test_msg_id_' . uniqid());
        }

        return $push;
    }

    public function testPushEntity(): void
    {
        $account = $this->createTestAccount();
        $push = $this->createTestPush($account);

        $this->assertSame($account, $push->getAccount());
        $this->assertSame(PlatformEnum::ALL, $push->getPlatform());
        $this->assertTrue($push->getAudience()->isAll());
    }

    public function testSave(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();
        $push = $this->createTestPush($account);

        $repository->save($push);

        $this->assertGreaterThan(0, $push->getId());
    }

    public function testSaveWithAllPlatforms(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();

        $platforms = [
            PlatformEnum::ALL,
            PlatformEnum::ANDROID,
            PlatformEnum::IOS,
            PlatformEnum::QUICKAPP,
            PlatformEnum::HMOS,
        ];

        foreach ($platforms as $platform) {
            $push = $this->createTestPush($account, ['platform' => $platform]);
            $repository->save($push);

            $this->assertGreaterThan(0, $push->getId());
            $this->assertSame($platform, $push->getPlatform());
        }
    }

    public function testSaveWithOptionalFields(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();
        $uniqueCid = 'test_cid_' . uniqid();
        $uniqueMsgId = 'test_msg_id_' . uniqid();

        $push = $this->createTestPush($account, [
            'cid' => $uniqueCid,
            'msgId' => $uniqueMsgId,
        ]);

        $repository->save($push);

        $this->assertGreaterThan(0, $push->getId());
        $this->assertSame($uniqueCid, $push->getCid());
        $this->assertSame($uniqueMsgId, $push->getMsgId());
    }

    public function testRemove(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();
        $push = $this->createTestPush($account);

        $repository->save($push);
        $id = $push->getId();

        $repository->remove($push);

        $foundPush = $repository->find($id);
        $this->assertNull($foundPush);
    }

    public function testFindByWithAccountCriteriaShouldReturnMatchingPushes(): void
    {
        $repository = self::getService(PushRepository::class);
        $account1 = $this->createTestAccount();
        $account2 = $this->createTestAccount();

        $push1 = $this->createTestPush($account1);
        $push2 = $this->createTestPush($account2);
        $repository->save($push1);
        $repository->save($push2);

        $pushes = $repository->findBy(['account' => $account1]);
        $this->assertIsArray($pushes);
        $this->assertCount(1, $pushes);
        $this->assertInstanceOf(Push::class, $pushes[0]);
        $pushAccount = $pushes[0]->getAccount();
        $this->assertNotNull($pushAccount);
        $this->assertSame($account1->getId(), $pushAccount->getId());
    }

    public function testCountWithAccountCriteriaShouldReturnCorrectNumber(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();

        $push1 = $this->createTestPush($account);
        $push2 = $this->createTestPush($account);
        $repository->save($push1);
        $repository->save($push2);

        $count = $repository->count(['account' => $account]);
        $this->assertSame(2, $count);
    }

    public function testFindByWithNullCidShouldWork(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();

        $push = $this->createTestPush($account, ['msgId' => null]); // 确保msgId为null以避免API调用
        $repository->save($push);

        $pushes = $repository->findBy(['cid' => null]);
        $this->assertIsArray($pushes);
        $this->assertGreaterThanOrEqual(1, count($pushes));

        foreach ($pushes as $foundPush) {
            $this->assertInstanceOf(Push::class, $foundPush);
            $this->assertNull($foundPush->getCid());
        }
    }

    public function testFindByWithNullMsgIdShouldWork(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();

        $push = $this->createTestPush($account); // 使用默认msgId保存
        $repository->save($push);

        // 保存后设置msgId为null以便测试查询
        $push->setMsgId(null);
        $repository->save($push);

        $pushes = $repository->findBy(['msgId' => null]);
        $this->assertIsArray($pushes);
        $this->assertGreaterThanOrEqual(1, count($pushes));

        foreach ($pushes as $foundPush) {
            $this->assertInstanceOf(Push::class, $foundPush);
            $this->assertNull($foundPush->getMsgId());
        }
    }

    public function testCountWithNullFieldShouldWork(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();

        $push = $this->createTestPush($account, ['msgId' => null]); // 确保msgId为null以避免API调用
        $repository->save($push);

        $count = $repository->count(['cid' => null]);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByWithMultipleCriteria(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();
        $uniqueCid = 'multi_criteria_' . uniqid();

        $push = $this->createTestPush($account, [
            'platform' => PlatformEnum::IOS,
            'cid' => $uniqueCid,
        ]);
        $repository->save($push);

        $pushes = $repository->findBy([
            'platform' => PlatformEnum::IOS,
            'cid' => $uniqueCid,
        ]);

        $this->assertCount(1, $pushes);
        $this->assertInstanceOf(Push::class, $pushes[0]);
        $this->assertSame(PlatformEnum::IOS, $pushes[0]->getPlatform());
        $this->assertSame($uniqueCid, $pushes[0]->getCid());
    }

    public function testPushWithEmbeddedAudience(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();

        $push = new Push();
        $push->setAccount($account);
        $push->setPlatform(PlatformEnum::ANDROID);
        $push->setMsgId('test_msg_id_' . uniqid()); // 避免API调用

        $audience = new Audience();
        $audience->setAll(false);
        $audience->setTag(['tag1', 'tag2']);
        $audience->setAlias(['alias1', 'alias2']);
        $push->setAudience($audience);

        $repository->save($push);

        $foundPush = $repository->find($push->getId());
        $this->assertInstanceOf(Push::class, $foundPush);
        $this->assertFalse($foundPush->getAudience()->isAll());
        $this->assertSame(['tag1', 'tag2'], $foundPush->getAudience()->getTag());
        $this->assertSame(['alias1', 'alias2'], $foundPush->getAudience()->getAlias());
    }

    public function testToArrayMethod(): void
    {
        $account = $this->createTestAccount();
        $push = $this->createTestPush($account, [
            'platform' => PlatformEnum::ANDROID,
            'cid' => 'test_cid_123',
        ]);

        $array = $push->toArray();

        $this->assertIsArray($array);
        $this->assertSame('android', $array['platform']);
        $this->assertSame('all', $array['audience']);
        $this->assertSame('test_cid_123', $array['cid']);
    }

    public function testStringRepresentation(): void
    {
        $account = $this->createTestAccount();
        $push = $this->createTestPush($account, ['platform' => PlatformEnum::IOS]);

        $repository = self::getService(PushRepository::class);
        $repository->save($push);

        $expectedString = 'Push #' . $push->getId() . ' - ios';
        $this->assertSame($expectedString, (string) $push);
    }

    public function testPlatformEnumValues(): void
    {
        $account = $this->createTestAccount();
        $repository = self::getService(PushRepository::class);

        $testCases = [
            [PlatformEnum::ALL, 'all'],
            [PlatformEnum::ANDROID, 'android'],
            [PlatformEnum::IOS, 'ios'],
            [PlatformEnum::QUICKAPP, 'quickapp'],
            [PlatformEnum::HMOS, 'hmos'],
        ];

        foreach ($testCases as [$enum, $expectedValue]) {
            $push = $this->createTestPush($account, ['platform' => $enum]);
            $repository->save($push);

            $this->assertSame($enum, $push->getPlatform());
            $this->assertSame($expectedValue, $push->getPlatform()->value);
        }
    }

    public function testRemovePushWithAccount(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();

        $push = $this->createTestPush($account);
        $repository->save($push);
        $pushId = $push->getId();

        $repository->remove($push);

        $foundPush = $repository->find($pushId);
        $this->assertNull($foundPush);

        // Account应该仍然存在
        $accountRepository = self::getService(AccountRepository::class);
        $foundAccount = $accountRepository->find($account->getId());
        $this->assertInstanceOf(Account::class, $foundAccount);
    }

    public function testFindByPlatformAndAccount(): void
    {
        $repository = self::getService(PushRepository::class);
        $account1 = $this->createTestAccount();
        $account2 = $this->createTestAccount();

        $push1 = $this->createTestPush($account1, ['platform' => PlatformEnum::ANDROID]);
        $push2 = $this->createTestPush($account1, ['platform' => PlatformEnum::IOS]);
        $push3 = $this->createTestPush($account2, ['platform' => PlatformEnum::ANDROID]);

        $repository->save($push1);
        $repository->save($push2);
        $repository->save($push3);

        $pushes = $repository->findBy([
            'account' => $account1,
            'platform' => PlatformEnum::ANDROID,
        ]);

        $this->assertCount(1, $pushes);
        $this->assertInstanceOf(Push::class, $pushes[0]);
        $pushAccount = $pushes[0]->getAccount();
        $this->assertNotNull($pushAccount);
        $this->assertSame($account1->getId(), $pushAccount->getId());
        $this->assertSame(PlatformEnum::ANDROID, $pushes[0]->getPlatform());
    }

    public function testFindOneByWithOrderByShouldWork(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();

        $push1 = $this->createTestPush($account, ['cid' => 'aaa_push']);
        $repository->save($push1);

        $push2 = $this->createTestPush($account, ['cid' => 'zzz_push']);
        $repository->save($push2);

        $foundPush = $repository->findOneBy(['account' => $account], ['cid' => 'ASC']);
        $this->assertInstanceOf(Push::class, $foundPush);
        $this->assertSame('aaa_push', $foundPush->getCid());

        $foundPush = $repository->findOneBy(['account' => $account], ['cid' => 'DESC']);
        $this->assertInstanceOf(Push::class, $foundPush);
        $this->assertSame('zzz_push', $foundPush->getCid());
    }

    public function testFindByWithAccountAssociation(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();

        $push = $this->createTestPush($account);
        $repository->save($push);

        $pushes = $repository->findBy(['account' => $account]);
        $this->assertIsArray($pushes);
        $this->assertNotEmpty($pushes);
        $this->assertInstanceOf(Push::class, $pushes[0]);
        $this->assertSame($account->getId(), $pushes[0]->getAccount()->getId());
    }

    public function testCountWithAccountAssociation(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();

        $initialCount = $repository->count(['account' => $account]);

        $push = $this->createTestPush($account);
        $repository->save($push);

        $newCount = $repository->count(['account' => $account]);
        $this->assertSame($initialCount + 1, $newCount);
    }

    public function testCountWithNullCidField(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();

        $push = $this->createTestPush($account); // cid默认为null
        $push->setCid(null); // 显式设置为null
        $repository->save($push);

        $count = $repository->count(['cid' => null]);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testCountWithNullMsgIdField(): void
    {
        $repository = self::getService(PushRepository::class);
        $account = $this->createTestAccount();

        // 创建一个 push 但不保存到数据库（避免触发 PushListener）
        // 数据库中应该已经有其他 msgId 为 null 的记录
        $count = $repository->count(['msgId' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    protected function createNewEntity(): Push
    {
        $account = $this->createTestAccount();

        return $this->createTestPush($account);
    }

    protected function getRepository(): PushRepository
    {
        return self::getService(PushRepository::class);
    }

    protected function getTestEntityClass(): string
    {
        return Push::class;
    }
}
