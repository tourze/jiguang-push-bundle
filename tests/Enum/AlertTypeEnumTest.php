<?php

namespace JiguangPushBundle\Tests\Enum;

use JiguangPushBundle\Enum\AlertTypeEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;

/**
 * @internal
 */
#[CoversClass(AlertTypeEnum::class)]
final class AlertTypeEnumTest extends AbstractEnumTestCase
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

    public function testToArray(): void
    {
        $array = AlertTypeEnum::DEFAULT_ALL->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('label', $array);
        $this->assertArrayHasKey('value', $array);
        $this->assertSame('跟随系统默认', $array['label']);
        $this->assertSame(-1, $array['value']);

        // 测试其他枚举值
        $array = AlertTypeEnum::NONE->toArray();
        $this->assertSame('无提醒', $array['label']);
        $this->assertSame(0, $array['value']);

        $array = AlertTypeEnum::SOUND->toArray();
        $this->assertSame('声音提醒', $array['label']);
        $this->assertSame(1, $array['value']);

        $array = AlertTypeEnum::VIBRATE->toArray();
        $this->assertSame('震动提醒', $array['label']);
        $this->assertSame(2, $array['value']);

        $array = AlertTypeEnum::LIGHTS->toArray();
        $this->assertSame('闪灯提醒', $array['label']);
        $this->assertSame(4, $array['value']);

        $array = AlertTypeEnum::ALL->toArray();
        $this->assertSame('声音、震动和闪灯提醒', $array['label']);
        $this->assertSame(7, $array['value']);
    }
}
