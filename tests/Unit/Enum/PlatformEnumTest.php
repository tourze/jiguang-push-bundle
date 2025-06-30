<?php

namespace JiguangPushBundle\Tests\Unit\Enum;

use JiguangPushBundle\Enum\PlatformEnum;
use PHPUnit\Framework\TestCase;

class PlatformEnumTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertSame('all', PlatformEnum::ALL->value);
        $this->assertSame('android', PlatformEnum::ANDROID->value);
        $this->assertSame('ios', PlatformEnum::IOS->value);
        $this->assertSame('quickapp', PlatformEnum::QUICKAPP->value);
        $this->assertSame('hmos', PlatformEnum::HMOS->value);
    }

    public function testGetLabel(): void
    {
        $this->assertSame('所有平台', PlatformEnum::ALL->getLabel());
        $this->assertSame('Android', PlatformEnum::ANDROID->getLabel());
        $this->assertSame('iOS', PlatformEnum::IOS->getLabel());
        $this->assertSame('快应用', PlatformEnum::QUICKAPP->getLabel());
        $this->assertSame('鸿蒙', PlatformEnum::HMOS->getLabel());
    }
}