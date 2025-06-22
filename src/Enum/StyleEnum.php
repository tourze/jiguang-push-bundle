<?php

namespace JiguangPushBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 通知栏样式类型。
 */
enum StyleEnum: int implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case DEFAULT = 0;
    case BIG_TEXT = 1;
    case INBOX = 2;
    case BIG_PICTURE = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::DEFAULT => '默认样式',
            self::BIG_TEXT => '大文本样式',
            self::INBOX => '收件箱样式',
            self::BIG_PICTURE => '大图片样式',
        };
    }
}
