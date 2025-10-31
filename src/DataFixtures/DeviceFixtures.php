<?php

namespace JiguangPushBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Device;
use JiguangPushBundle\Entity\Tag;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
#[When(env: 'dev')]
class DeviceFixtures extends Fixture implements DependentFixtureInterface
{
    public const DEVICE_1_REFERENCE = 'device-1';
    public const DEVICE_2_REFERENCE = 'device-2';

    public function load(ObjectManager $manager): void
    {
        $account = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE, Account::class);
        assert($account instanceof Account);
        $tagVip = $this->getReference(TagFixtures::TAG_VIP_REFERENCE, Tag::class);
        assert($tagVip instanceof Tag);
        $tagNew = $this->getReference(TagFixtures::TAG_NEW_REFERENCE, Tag::class);
        assert($tagNew instanceof Tag);

        $device1 = new Device();
        $device1->setAccount($account);
        $device1->setRegistrationId('test_registration_id_001');
        $device1->setAlias('测试设备1');
        $device1->setMobile('13800138001');
        $device1->addTag($tagVip);

        $device2 = new Device();
        $device2->setAccount($account);
        $device2->setRegistrationId('test_registration_id_002');
        $device2->setAlias('测试设备2');
        $device2->setMobile('13800138002');
        $device2->addTag($tagNew);

        $manager->persist($device1);
        $manager->persist($device2);
        $manager->flush();

        $this->addReference(self::DEVICE_1_REFERENCE, $device1);
        $this->addReference(self::DEVICE_2_REFERENCE, $device2);
    }

    public function getDependencies(): array
    {
        return [
            AccountFixtures::class,
            TagFixtures::class,
        ];
    }
}
