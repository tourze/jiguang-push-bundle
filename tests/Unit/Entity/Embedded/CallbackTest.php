<?php

namespace JiguangPushBundle\Tests\Unit\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\Callback;
use PHPUnit\Framework\TestCase;

class CallbackTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $callback = new Callback();
        
        $callback->setUrl('https://example.com/callback');
        $this->assertSame('https://example.com/callback', $callback->getUrl());
        
        $callback->setParams(['key' => 'value']);
        $this->assertSame(['key' => 'value'], $callback->getParams());
        
        $callback->setType('callback_type');
        $this->assertSame('callback_type', $callback->getType());
    }
}