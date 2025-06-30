<?php

namespace JiguangPushBundle\Tests\Unit\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\IosNotification;
use PHPUnit\Framework\TestCase;

class IosNotificationTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $iosNotification = new IosNotification();
        
        $iosNotification->setAlert('Test alert');
        $this->assertSame('Test alert', $iosNotification->getAlert());
        
        $iosNotification->setBadge(5);
        $this->assertSame(5, $iosNotification->getBadge());
        
        $iosNotification->setSound('sound.wav');
        $this->assertSame('sound.wav', $iosNotification->getSound());
        
        $iosNotification->setContentAvailable(true);
        $this->assertTrue($iosNotification->isContentAvailable());
        
        $iosNotification->setMutableContent(true);
        $this->assertTrue($iosNotification->isMutableContent());
        
        $iosNotification->setCategory('category');
        $this->assertSame('category', $iosNotification->getCategory());
        
        $iosNotification->setExtras(['key' => 'value']);
        $this->assertSame(['key' => 'value'], $iosNotification->getExtras());
    }
}