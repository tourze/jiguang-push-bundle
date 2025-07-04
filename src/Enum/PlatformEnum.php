<?php

namespace JiguangPushBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum PlatformEnum: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case ALL = 'all';
    case ANDROID = 'android';
    case IOS = 'ios';
    case QUICKAPP = 'quickapp';
    case HMOS = 'hmos';

    public function getLabel(): string
    {
        return match ($this) {
            self::ALL => '所有平台',
            self::ANDROID => 'Android',
            self::IOS => 'iOS',
            self::QUICKAPP => '快应用',
            self::HMOS => '鸿蒙',
        };
    }
}
