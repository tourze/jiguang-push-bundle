<?php

namespace JiguangPushBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use JiguangPushBundle\Entity\Push;
use JiguangPushBundle\Repository\PushRepository;
use PHPUnit\Framework\TestCase;

class PushRepositoryTest extends TestCase
{
    public function testRepositoryClassName(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new PushRepository($registry);
        
        $this->assertInstanceOf(PushRepository::class, $repository);
    }

    public function testPushEntity(): void
    {
        $push = new Push();
        $push->setMsgId('test_msg_id');
        
        $this->assertSame('test_msg_id', $push->getMsgId());
    }
}