<?php

namespace JiguangPushBundle\Enum;

/**
 * 通知提醒方式。
 * 
 * 二进制表示：0111
 * - 左数第二位代表 light
 * - 第三位代表 vibrate
 * - 第四位代表 sound
 * 0：不生效，1：生效
 */
enum AlertTypeEnum: int
{
    /**
     * 跟随系统默认。
     */
    case DEFAULT_ALL = -1;

    /**
     * 无提醒。
     */
    case NONE = 0;

    /**
     * 声音提醒。
     */
    case SOUND = 1;

    /**
     * 震动提醒。
     */
    case VIBRATE = 2;

    /**
     * 声音和震动提醒。
     */
    case SOUND_VIBRATE = 3;

    /**
     * 闪灯提醒。
     */
    case LIGHTS = 4;

    /**
     * 声音和闪灯提醒。
     */
    case SOUND_LIGHTS = 5;

    /**
     * 震动和闪灯提醒。
     */
    case VIBRATE_LIGHTS = 6;

    /**
     * 声音、震动和闪灯提醒。
     */
    case ALL = 7;
}
