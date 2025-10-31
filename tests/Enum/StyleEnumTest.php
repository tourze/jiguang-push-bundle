<?php

namespace JiguangPushBundle\Tests\Enum;

use JiguangPushBundle\Enum\StyleEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;

/**
 * @internal
 */
#[CoversClass(StyleEnum::class)]
final class StyleEnumTest extends AbstractEnumTestCase
{
    public function testEnumValues(): void
    {
        $this->assertSame(0, StyleEnum::DEFAULT->value);
        $this->assertSame(1, StyleEnum::BIG_TEXT->value);
        $this->assertSame(2, StyleEnum::INBOX->value);
        $this->assertSame(3, StyleEnum::BIG_PICTURE->value);
    }

    public function testGetLabel(): void
    {
        $this->assertSame('默认样式', StyleEnum::DEFAULT->getLabel());
        $this->assertSame('大文本样式', StyleEnum::BIG_TEXT->getLabel());
        $this->assertSame('收件箱样式', StyleEnum::INBOX->getLabel());
        $this->assertSame('大图片样式', StyleEnum::BIG_PICTURE->getLabel());
    }

    public function testToArray(): void
    {
        $array = StyleEnum::DEFAULT->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('label', $array);
        $this->assertArrayHasKey('value', $array);
        $this->assertSame('默认样式', $array['label']);
        $this->assertSame(0, $array['value']);

        // 测试其他枚举值
        $array = StyleEnum::BIG_TEXT->toArray();
        $this->assertSame('大文本样式', $array['label']);
        $this->assertSame(1, $array['value']);

        $array = StyleEnum::INBOX->toArray();
        $this->assertSame('收件箱样式', $array['label']);
        $this->assertSame(2, $array['value']);

        $array = StyleEnum::BIG_PICTURE->toArray();
        $this->assertSame('大图片样式', $array['label']);
        $this->assertSame(3, $array['value']);
    }
}
