<?php

namespace JiguangPushBundle\Tests\Unit\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\AndroidNotificationExtras;
use PHPUnit\Framework\TestCase;

class AndroidNotificationExtrasTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $extras = new AndroidNotificationExtras();
        
        $extras->setMipnsContentForshort('小米长描述');
        $this->assertSame('小米长描述', $extras->getMipnsContentForshort());
        
        $extras->setOppnsContentForshort('OPPO长描述');
        $this->assertSame('OPPO长描述', $extras->getOppnsContentForshort());
        
        $extras->setVpnsContentForshort('vivo长描述');
        $this->assertSame('vivo长描述', $extras->getVpnsContentForshort());
        
        $extras->setMzpnsContentForshort('魅族长描述');
        $this->assertSame('魅族长描述', $extras->getMzpnsContentForshort());
    }

    public function testToArray(): void
    {
        $extras = new AndroidNotificationExtras();
        $extras->setMipnsContentForshort('小米长描述');
        $extras->setOppnsContentForshort('OPPO长描述');
        
        $expected = [
            'mipns_content_forshort' => '小米长描述',
            'oppns_content_forshort' => 'OPPO长描述',
        ];
        
        $this->assertSame($expected, $extras->toArray());
    }
}