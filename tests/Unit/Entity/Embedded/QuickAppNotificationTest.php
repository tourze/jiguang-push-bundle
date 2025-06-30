<?php

namespace JiguangPushBundle\Tests\Unit\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\QuickAppNotification;
use PHPUnit\Framework\TestCase;

class QuickAppNotificationTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $quickAppNotification = new QuickAppNotification();
        
        $quickAppNotification->setAlert('Test alert');
        $this->assertSame('Test alert', $quickAppNotification->getAlert());
        
        $quickAppNotification->setTitle('Test title');
        $this->assertSame('Test title', $quickAppNotification->getTitle());
        
        $quickAppNotification->setPage('test_page');
        $this->assertSame('test_page', $quickAppNotification->getPage());
    }
}