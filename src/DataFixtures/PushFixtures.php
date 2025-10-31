<?php

namespace JiguangPushBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Embedded\Audience;
use JiguangPushBundle\Entity\Embedded\Message;
use JiguangPushBundle\Entity\Push;
use JiguangPushBundle\Enum\PlatformEnum;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
#[When(env: 'dev')]
class PushFixtures extends Fixture implements DependentFixtureInterface
{
    public const PUSH_REFERENCE = 'push';

    public function load(ObjectManager $manager): void
    {
        $account = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE, Account::class);
        assert($account instanceof Account);

        $audience = new Audience();
        $audience->setAll(true);

        $message = new Message();
        $message->setMsgContent('测试推送消息内容');
        $message->setTitle('测试推送');

        $push = new Push();
        $push->setAccount($account);
        $push->setPlatform(PlatformEnum::ALL);
        $push->setAudience($audience);
        $push->setMessage($message);
        $push->setCid('test_cid_001');
        $push->setMsgId('test_msg_id_001');

        $manager->persist($push);
        $manager->flush();

        $this->addReference(self::PUSH_REFERENCE, $push);
    }

    public function getDependencies(): array
    {
        return [
            AccountFixtures::class,
        ];
    }
}
