<?php

namespace JiguangPushBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Tag;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
#[When(env: 'dev')]
class TagFixtures extends Fixture implements DependentFixtureInterface
{
    public const TAG_VIP_REFERENCE = 'tag-vip';
    public const TAG_NEW_REFERENCE = 'tag-new';

    public function load(ObjectManager $manager): void
    {
        $account = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE, Account::class);
        assert($account instanceof Account);

        $tag1 = new Tag();
        $tag1->setAccount($account);
        $tag1->setValue('VIP用户');

        $tag2 = new Tag();
        $tag2->setAccount($account);
        $tag2->setValue('新用户');

        $manager->persist($tag1);
        $manager->persist($tag2);
        $manager->flush();

        $this->addReference(self::TAG_VIP_REFERENCE, $tag1);
        $this->addReference(self::TAG_NEW_REFERENCE, $tag2);
    }

    public function getDependencies(): array
    {
        return [
            AccountFixtures::class,
        ];
    }
}
