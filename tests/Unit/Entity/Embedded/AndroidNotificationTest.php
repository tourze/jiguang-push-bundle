<?php

namespace JiguangPushBundle\Tests\Unit\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\AndroidNotification;
use PHPUnit\Framework\TestCase;

class AndroidNotificationTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $androidNotification = new AndroidNotification();
        
        $androidNotification->setAlert('Test alert');
        $this->assertSame('Test alert', $androidNotification->getAlert());
        
        $androidNotification->setTitle('Test title');
        $this->assertSame('Test title', $androidNotification->getTitle());
        
        $androidNotification->setBuilderId(123);
        $this->assertSame(123, $androidNotification->getBuilderId());
        
        $androidNotification->setPriority(1);
        $this->assertSame(1, $androidNotification->getPriority());
        
        $androidNotification->setCategory('Test category');
        $this->assertSame('Test category', $androidNotification->getCategory());
        
        $androidNotification->setStyle(\JiguangPushBundle\Enum\StyleEnum::BIG_TEXT);
        $this->assertSame(\JiguangPushBundle\Enum\StyleEnum::BIG_TEXT, $androidNotification->getStyle());
        
        $androidNotification->setAlertType(\JiguangPushBundle\Enum\AlertTypeEnum::SOUND);
        $this->assertSame(\JiguangPushBundle\Enum\AlertTypeEnum::SOUND, $androidNotification->getAlertType());
        
        $androidNotification->setBigText('Big text');
        $this->assertSame('Big text', $androidNotification->getBigText());
        
        $androidNotification->setInbox(['message1', 'message2']);
        $this->assertSame(['message1', 'message2'], $androidNotification->getInbox());
        
        $androidNotification->setBigPicPath('/path/to/pic');
        $this->assertSame('/path/to/pic', $androidNotification->getBigPicPath());
    }
}