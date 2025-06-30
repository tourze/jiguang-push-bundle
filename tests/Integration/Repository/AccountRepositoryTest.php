<?php

namespace JiguangPushBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Repository\AccountRepository;
use PHPUnit\Framework\TestCase;

class AccountRepositoryTest extends TestCase
{
    public function testRepositoryClassName(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new AccountRepository($registry);
        
        $this->assertInstanceOf(AccountRepository::class, $repository);
    }

    public function testAccountEntity(): void
    {
        $account = new Account();
        $account->setAppKey('test_app_key');
        
        $this->assertSame('test_app_key', $account->getAppKey());
    }
}