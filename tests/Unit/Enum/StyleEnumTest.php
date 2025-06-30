<?php

namespace JiguangPushBundle\Tests\Unit\Enum;

use JiguangPushBundle\Enum\StyleEnum;
use PHPUnit\Framework\TestCase;

class StyleEnumTest extends TestCase
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
}