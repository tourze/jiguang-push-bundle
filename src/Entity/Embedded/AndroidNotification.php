<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use JiguangPushBundle\Enum\AlertTypeEnum;
use JiguangPushBundle\Enum\StyleEnum;
use Tourze\Arrayable\Arrayable;

/**
 * Android 平台上的通知。
 *
 * @see https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push#android
 */
#[ORM\Embeddable]
class AndroidNotification implements Arrayable
{
    /**
     * 通知内容。
     * 这里指定了，则会覆盖上级统一指定的 alert 信息；内容可以为空字符串，则表示不展示到通知栏。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知内容'])]
    private ?string $alert = null;

    /**
     * 通知标题。
     * 如果指定了，则通知里原来展示 App名称的地方，将展示成这个字段。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知标题'])]
    private ?string $title = null;

    /**
     * 通知栏样式ID。
     * Android SDK 可设置通知栏样式，这里根据样式 ID 来指定该使用哪套样式。
     */
    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '通知栏样式ID'])]
    private ?int $builderId = null;

    /**
     * 通知渠道ID。
     * 不超过1000字节，如果不指定，则使用默认渠道。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知渠道ID'])]
    private ?string $channelId = null;

    /**
     * 通知栏条目过滤或排序。
     * 完全依赖 rom 厂商对 category 的处理策略。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知栏条目过滤或排序'])]
    private ?string $category = null;

    /**
     * 通知栏展示优先级。
     * 默认为0，范围为 -2～2。
     */
    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '通知栏展示优先级'])]
    private ?int $priority = null;

    /**
     * 通知栏样式类型。
     * 0: 默认样式
     * 1: 大文本
     * 2: 收件箱
     * 3: 大图片
     */
    #[ORM\Column(type: 'string', enumType: StyleEnum::class, options: ['comment' => '通知栏样式类型'])]
    private ?StyleEnum $style = null;

    /**
     * 通知提醒方式。
     * 可选范围为 -1～7。
     * -1: 跟随系统默认
     * 0: 无提醒
     * 1: 声音
     * 2: 震动
     * 3: 声音和震动
     * 4: 闪灯
     * 5: 声音和闪灯
     * 6: 震动和闪灯
     * 7: 声音、震动和闪灯
     */
    #[ORM\Column(type: 'string', enumType: AlertTypeEnum::class, options: ['comment' => '通知提醒方式'])]
    private ?AlertTypeEnum $alertType = null;

    /**
     * 大文本通知栏样式。
     * 当 style = 1 时可用，内容会被通知栏以大文本的形式展示出来。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '大文本通知栏样式'])]
    private ?string $bigText = null;

    /**
     * 收件箱通知栏样式。
     * 当 style = 3 时可用，为一个字符串数组，会被通知栏以收件箱的形式展示出来。
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '收件箱通知栏样式'])]
    private ?array $inbox = null;

    /**
     * 大图片通知栏样式。
     * 当 style = 2 时可用，可以是网络图片url，或本地图片的路径（如：/sdcard/img/notification.png）。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '大图片通知栏样式'])]
    private ?string $bigPicPath = null;

    /**
     * 扩展字段。
     * 这里自定义 JSON 格式的 Key/Value 信息，以供业务使用。
     */
    #[ORM\Embedded(class: AndroidNotificationExtras::class)]
    private ?AndroidNotificationExtras $extras = null;

    /**
     * 通知栏大图标。
     * 图标路径可以是以http或https开头的网络图片，如：http://www.jiguang.cn/largeIcon.jpg
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知栏大图标'])]
    private ?string $largeIcon = null;

    /**
     * 通知栏小图标。
     * 图标路径可以是以http或https开头的网络图片，如：http://www.jiguang.cn/smallIcon.jpg
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知栏小图标'])]
    private ?string $smallIconUri = null;

    /**
     * 通知栏小图标背景颜色。
     * 格式如：#FF0000。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知栏小图标背景颜色'])]
    private ?string $iconBgColor = null;

    /**
     * 指定跳转页面。
     * 指定跳转页面（仅安卓支持），该字段为 JSON 格式。
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '指定跳转页面'])]
    private ?array $intent = null;

    /**
     * 指定跳转的Activity。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '指定跳转的Activity'])]
    private ?string $uriActivity = null;

    /**
     * 指定跳转的Action。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '指定跳转的Action'])]
    private ?string $uriAction = null;

    /**
     * 角标数字加数量。
     */
    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '角标数字加数量'])]
    private ?int $badgeAddNum = null;

    /**
     * 角标数字设置数量。
     */
    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '角标数字设置数量'])]
    private ?int $badgeSetNum = null;

    /**
     * 桌面图标对应的应用入口Activity类。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '桌面图标对应的应用入口Activity类'])]
    private ?string $badgeClass = null;

    /**
     * 定时展示开始时间（yyyy-MM-dd HH:mm:ss）。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '定时展示开始时间'])]
    private ?string $showBeginTime = null;

    /**
     * 定时展示结束时间（yyyy-MM-dd HH:mm:ss）。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '定时展示结束时间'])]
    private ?string $showEndTime = null;

    /**
     * APP在前台时是否展示通知。
     */
    #[ORM\Column(type: 'boolean', nullable: true, options: ['comment' => 'APP在前台时是否展示通知'])]
    private ?bool $displayForeground = null;

    /**
     * 通知提示音。
     * 如果指定，必须是应用内的资源文件名，文件名不能包含路径或文件扩展名。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知提示音'])]
    private ?string $sound = null;

    public function getAlert(): ?string
    {
        return $this->alert;
    }

    public function setAlert(?string $alert): static
    {
        $this->alert = $alert;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getBuilderId(): ?int
    {
        return $this->builderId;
    }

    public function setBuilderId(?int $builderId): static
    {
        $this->builderId = $builderId;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getStyle(): ?StyleEnum
    {
        return $this->style;
    }

    public function setStyle(?StyleEnum $style): static
    {
        $this->style = $style;

        return $this;
    }

    public function getAlertType(): ?AlertTypeEnum
    {
        return $this->alertType;
    }

    public function setAlertType(?AlertTypeEnum $alertType): static
    {
        $this->alertType = $alertType;

        return $this;
    }

    public function getLargeIcon(): ?string
    {
        return $this->largeIcon;
    }

    public function setLargeIcon(?string $largeIcon): static
    {
        $this->largeIcon = $largeIcon;

        return $this;
    }

    public function getIntent(): ?array
    {
        return $this->intent;
    }

    public function setIntent(?array $intent): static
    {
        $this->intent = $intent;

        return $this;
    }

    public function getExtras(): ?AndroidNotificationExtras
    {
        return $this->extras;
    }

    public function setExtras(?AndroidNotificationExtras $extras): static
    {
        $this->extras = $extras;

        return $this;
    }

    public function getSound(): ?string
    {
        return $this->sound;
    }

    public function setSound(?string $sound): static
    {
        $this->sound = $sound;

        return $this;
    }

    public function getBigText(): ?string
    {
        return $this->bigText;
    }

    public function setBigText(?string $bigText): static
    {
        $this->bigText = $bigText;

        return $this;
    }

    public function getBigPicPath(): ?string
    {
        return $this->bigPicPath;
    }

    public function setBigPicPath(?string $bigPicPath): static
    {
        $this->bigPicPath = $bigPicPath;

        return $this;
    }

    public function getInbox(): ?array
    {
        return $this->inbox;
    }

    public function setInbox(?array $inbox): static
    {
        $this->inbox = $inbox;

        return $this;
    }

    public function getChannelId(): ?string
    {
        return $this->channelId;
    }

    public function setChannelId(?string $channelId): static
    {
        $this->channelId = $channelId;

        return $this;
    }

    public function getSmallIconUri(): ?string
    {
        return $this->smallIconUri;
    }

    public function setSmallIconUri(?string $smallIconUri): static
    {
        $this->smallIconUri = $smallIconUri;

        return $this;
    }

    public function getIconBgColor(): ?string
    {
        return $this->iconBgColor;
    }

    public function setIconBgColor(?string $iconBgColor): static
    {
        $this->iconBgColor = $iconBgColor;

        return $this;
    }

    public function getUriActivity(): ?string
    {
        return $this->uriActivity;
    }

    public function setUriActivity(?string $uriActivity): static
    {
        $this->uriActivity = $uriActivity;

        return $this;
    }

    public function getUriAction(): ?string
    {
        return $this->uriAction;
    }

    public function setUriAction(?string $uriAction): static
    {
        $this->uriAction = $uriAction;

        return $this;
    }

    public function getBadgeAddNum(): ?int
    {
        return $this->badgeAddNum;
    }

    public function setBadgeAddNum(?int $badgeAddNum): static
    {
        $this->badgeAddNum = $badgeAddNum;

        return $this;
    }

    public function getBadgeSetNum(): ?int
    {
        return $this->badgeSetNum;
    }

    public function setBadgeSetNum(?int $badgeSetNum): static
    {
        $this->badgeSetNum = $badgeSetNum;

        return $this;
    }

    public function getBadgeClass(): ?string
    {
        return $this->badgeClass;
    }

    public function setBadgeClass(?string $badgeClass): static
    {
        $this->badgeClass = $badgeClass;

        return $this;
    }

    public function getShowBeginTime(): ?string
    {
        return $this->showBeginTime;
    }

    public function setShowBeginTime(?string $showBeginTime): static
    {
        $this->showBeginTime = $showBeginTime;

        return $this;
    }

    public function getShowEndTime(): ?string
    {
        return $this->showEndTime;
    }

    public function setShowEndTime(?string $showEndTime): static
    {
        $this->showEndTime = $showEndTime;

        return $this;
    }

    public function isDisplayForeground(): ?bool
    {
        return $this->displayForeground;
    }

    public function setDisplayForeground(?bool $displayForeground): static
    {
        $this->displayForeground = $displayForeground;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'alert' => $this->getAlert(),
            'title' => $this->getTitle(),
            'builder_id' => $this->getBuilderId(),
            'priority' => $this->getPriority(),
            'category' => $this->getCategory(),
            'style' => $this->getStyle()?->value,
            'alert_type' => $this->getAlertType()?->value,
            'big_text' => $this->getBigText(),
            'big_pic_path' => $this->getBigPicPath(),
            'inbox' => $this->getInbox(),
            'large_icon' => $this->getLargeIcon(),
            'small_icon_uri' => $this->getSmallIconUri(),
            'icon_bg_color' => $this->getIconBgColor(),
            'intent' => $this->getIntent(),
            'uri_activity' => $this->getUriActivity(),
            'uri_action' => $this->getUriAction(),
            'badge_add_num' => $this->getBadgeAddNum(),
            'badge_set_num' => $this->getBadgeSetNum(),
            'badge_class' => $this->getBadgeClass(),
            'show_begin_time' => $this->getShowBeginTime(),
            'show_end_time' => $this->getShowEndTime(),
            'display_foreground' => $this->isDisplayForeground(),
            'extras' => $this->getExtras()?->toArray(),
            'sound' => $this->getSound(),
        ]);
    }
}
