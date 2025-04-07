<?php

namespace JiguangPushBundle\Enum;

enum PlatformEnum: string
{
    case ALL = 'all';
    case ANDROID = 'android';
    case IOS = 'ios';
    case QUICKAPP = 'quickapp';
    case HMOS = 'hmos';
}
