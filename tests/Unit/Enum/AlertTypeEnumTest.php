<?php

namespace JiguangPushBundle\Tests\Unit\Enum;

use JiguangPushBundle\Enum\AlertTypeEnum;
use PHPUnit\Framework\TestCase;

class AlertTypeEnumTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertSame(-1, AlertTypeEnum::DEFAULT_ALL->value);
        $this->assertSame(0, AlertTypeEnum::NONE->value);
        $this->assertSame(1, AlertTypeEnum::SOUND->value);
        $this->assertSame(2, AlertTypeEnum::VIBRATE->value);
        $this->assertSame(4, AlertTypeEnum::LIGHTS->value);
        $this->assertSame(7, AlertTypeEnum::ALL->value);
    }

    public function testGetLabel(): void
    {
        $this->assertSame('跟随系统默认', AlertTypeEnum::DEFAULT_ALL->getLabel());
        $this->assertSame('无提醒', AlertTypeEnum::NONE->getLabel());
        $this->assertSame('声音提醒', AlertTypeEnum::SOUND->getLabel());
        $this->assertSame('震动提醒', AlertTypeEnum::VIBRATE->getLabel());
        $this->assertSame('闪灯提醒', AlertTypeEnum::LIGHTS->getLabel());
        $this->assertSame('声音、震动和闪灯提醒', AlertTypeEnum::ALL->getLabel());
    }
}