<?php

namespace JiguangPushBundle\Tests\Unit\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\HmosNotification;
use PHPUnit\Framework\TestCase;

class HmosNotificationTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $hmosNotification = new HmosNotification();
        
        $hmosNotification->setAlert('Test alert');
        $this->assertSame('Test alert', $hmosNotification->getAlert());
        
        $hmosNotification->setTitle('Test title');
        $this->assertSame('Test title', $hmosNotification->getTitle());
        
        $hmosNotification->setCategory('Test category');
        $this->assertSame('Test category', $hmosNotification->getCategory());
        
        $hmosNotification->setIntent(['action' => 'test']);
        $this->assertSame(['action' => 'test'], $hmosNotification->getIntent());
        
        $hmosNotification->setExtras(['key' => 'value']);
        $this->assertSame(['key' => 'value'], $hmosNotification->getExtras());
    }
}