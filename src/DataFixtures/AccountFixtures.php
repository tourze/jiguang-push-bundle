<?php

namespace JiguangPushBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use JiguangPushBundle\Entity\Account;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
#[When(env: 'dev')]
class AccountFixtures extends Fixture
{
    public const ACCOUNT_REFERENCE = 'account';

    public function load(ObjectManager $manager): void
    {
        $account = new Account();
        $account->setTitle('测试极光账号');
        $account->setAppKey('test_app_key_12345');
        $account->setMasterSecret('test_master_secret_67890');
        $account->setValid(true);

        $manager->persist($account);
        $manager->flush();

        $this->addReference(self::ACCOUNT_REFERENCE, $account);
    }
}
