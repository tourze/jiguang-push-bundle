<?php

namespace JiguangPushBundle\Tests\Unit\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\LiveActivity;
use PHPUnit\Framework\TestCase;

class LiveActivityTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $liveActivity = new LiveActivity();
        
        $liveActivity->setActivityId('activity123');
        $this->assertSame('activity123', $liveActivity->getActivityId());
        
        $liveActivity->setContentState(['state' => 'active']);
        $this->assertSame(['state' => 'active'], $liveActivity->getContentState());
        
        $liveActivity->setEvent(['event' => 'update']);
        $this->assertSame(['event' => 'update'], $liveActivity->getEvent());
        
        $liveActivity->setTimestamp(1234567890);
        $this->assertSame(1234567890, $liveActivity->getTimestamp());
        
        $liveActivity->setPushToken('push_token_123');
        $this->assertSame('push_token_123', $liveActivity->getPushToken());
        
        $liveActivity->setRelevanceScore(10);
        $this->assertSame(10, $liveActivity->getRelevanceScore());
    }
}