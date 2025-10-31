<?php

namespace JiguangPushBundle\Tests\Repository;

use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Repository\AccountRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(AccountRepository::class)]
#[RunTestsInSeparateProcesses]
final class AccountRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 无需特殊设置
    }

    public function testAccountEntity(): void
    {
        $account = new Account();
        $account->setAppKey('test_app_key');

        $this->assertSame('test_app_key', $account->getAppKey());
    }

    public function testSave(): void
    {
        $repository = self::getService(AccountRepository::class);
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key_unique_' . uniqid());
        $account->setMasterSecret('test_master_secret');

        $repository->save($account);

        $this->assertGreaterThan(0, $account->getId());
    }

    public function testRemove(): void
    {
        $repository = self::getService(AccountRepository::class);
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key_unique_' . uniqid());
        $account->setMasterSecret('test_master_secret');

        $repository->save($account);
        $id = $account->getId();

        $repository->remove($account);

        $foundAccount = $repository->find($id);
        $this->assertNull($foundAccount);
    }

    public function testCountWithNullFieldShouldWork(): void
    {
        $repository = self::getService(AccountRepository::class);

        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_key_' . uniqid());
        $account->setMasterSecret('secret');
        $account->setValid(null);
        $repository->save($account);

        $count = $repository->count(['valid' => null]);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByWithNullFieldShouldWork(): void
    {
        $repository = self::getService(AccountRepository::class);

        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_key_' . uniqid());
        $account->setMasterSecret('secret');
        $account->setValid(null);
        $repository->save($account);

        $accounts = $repository->findBy(['valid' => null]);
        $this->assertIsArray($accounts);
        $this->assertNotEmpty($accounts);
    }

    public function testFindOneByWithOrderByShouldWork(): void
    {
        $repository = self::getService(AccountRepository::class);

        $account1 = new Account();
        $account1->setTitle('Account A');
        $account1->setAppKey('key_a_' . uniqid());
        $account1->setMasterSecret('secret');
        $repository->save($account1);

        $account2 = new Account();
        $account2->setTitle('Account Z');
        $account2->setAppKey('key_z_' . uniqid());
        $account2->setMasterSecret('secret');
        $repository->save($account2);

        $foundAccount = $repository->findOneBy(['masterSecret' => 'secret'], ['title' => 'ASC']);
        $this->assertInstanceOf(Account::class, $foundAccount);
        $this->assertSame('Account A', $foundAccount->getTitle());

        $foundAccount = $repository->findOneBy(['masterSecret' => 'secret'], ['title' => 'DESC']);
        $this->assertInstanceOf(Account::class, $foundAccount);
        $this->assertSame('Account Z', $foundAccount->getTitle());
    }

    protected function createNewEntity(): Account
    {
        $account = new Account();
        $account->setTitle('测试账号_' . uniqid());
        $account->setAppKey('test_app_key_' . uniqid());
        $account->setMasterSecret('test_master_secret');
        $account->setValid(true);

        return $account;
    }

    protected function getRepository(): AccountRepository
    {
        return self::getService(AccountRepository::class);
    }

    protected function getTestEntityClass(): string
    {
        return Account::class;
    }
}
