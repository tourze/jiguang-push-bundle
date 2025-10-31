<?php

namespace JiguangPushBundle\Tests\Enum;

use JiguangPushBundle\Enum\PlatformEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;

/**
 * @internal
 */
#[CoversClass(PlatformEnum::class)]
final class PlatformEnumTest extends AbstractEnumTestCase
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

    public function testToArray(): void
    {
        $array = PlatformEnum::ALL->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('label', $array);
        $this->assertArrayHasKey('value', $array);
        $this->assertSame('所有平台', $array['label']);
        $this->assertSame('all', $array['value']);

        // 测试其他枚举值
        $array = PlatformEnum::ANDROID->toArray();
        $this->assertSame('Android', $array['label']);
        $this->assertSame('android', $array['value']);

        $array = PlatformEnum::IOS->toArray();
        $this->assertSame('iOS', $array['label']);
        $this->assertSame('ios', $array['value']);

        $array = PlatformEnum::QUICKAPP->toArray();
        $this->assertSame('快应用', $array['label']);
        $this->assertSame('quickapp', $array['value']);

        $array = PlatformEnum::HMOS->toArray();
        $this->assertSame('鸿蒙', $array['label']);
        $this->assertSame('hmos', $array['value']);
    }
}
