<?php

namespace JiguangPushBundle\Tests\Unit\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\VoipNotification;
use PHPUnit\Framework\TestCase;

class VoipNotificationTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $voipNotification = new VoipNotification();
        
        $content = ['alert' => 'Test voip alert', 'badge' => 3];
        $voipNotification->setContent($content);
        $this->assertSame($content, $voipNotification->getContent());
    }

    public function testToArray(): void
    {
        $voipNotification = new VoipNotification();
        
        $content = ['alert' => 'Test voip alert', 'badge' => 3];
        $voipNotification->setContent($content);
        
        $this->assertSame($content, $voipNotification->toArray());
    }

    public function testToArrayEmpty(): void
    {
        $voipNotification = new VoipNotification();
        
        $this->assertSame([], $voipNotification->toArray());
    }
}