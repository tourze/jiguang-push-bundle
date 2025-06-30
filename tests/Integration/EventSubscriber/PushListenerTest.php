<?php

namespace JiguangPushBundle\Tests\Integration\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Push;
use JiguangPushBundle\EventSubscriber\PushListener;
use JiguangPushBundle\Service\JiguangService;
use PHPUnit\Framework\TestCase;

class PushListenerTest extends TestCase
{
    public function testPushListenerConstruction(): void
    {
        $jiguangService = $this->createMock(JiguangService::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        $listener = new PushListener($jiguangService, $entityManager);
        
        $this->assertInstanceOf(PushListener::class, $listener);
    }

    public function testPostPersist(): void
    {
        $jiguangService = $this->createMock(JiguangService::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        $jiguangService->expects($this->once())
            ->method('request')
            ->willReturn(['msg_id' => 'test_msg_id']);
        
        $entityManager->expects($this->once())
            ->method('persist');
        
        $entityManager->expects($this->once())
            ->method('flush');
        
        $account = new Account();
        $push = new Push();
        $push->setAccount($account);
        
        $listener = new PushListener($jiguangService, $entityManager);
        $listener->postPersist($push);
        
        $this->assertSame('test_msg_id', $push->getMsgId());
    }
}