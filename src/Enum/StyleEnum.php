<?php

namespace JiguangPushBundle\Enum;

/**
 * 通知栏样式类型。
 */
enum StyleEnum: int
{
    /**
     * 默认样式。
     */
    case DEFAULT = 0;

    /**
     * 大文本样式。
     */
    case BIG_TEXT = 1;

    /**
     * 收件箱样式。
     */
    case INBOX = 2;

    /**
     * 大图片样式。
     */
    case BIG_PICTURE = 3;
}
