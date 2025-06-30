<?php

namespace JiguangPushBundle\Tests\Unit;

use JiguangPushBundle\JiguangPushBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class JiguangPushBundleTest extends TestCase
{
    public function testBundleInstance(): void
    {
        $bundle = new JiguangPushBundle();
        
        $this->assertInstanceOf(Bundle::class, $bundle);
        $this->assertSame('JiguangPushBundle', $bundle->getName());
    }
}