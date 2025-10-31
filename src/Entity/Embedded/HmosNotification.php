<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

/**
 * HarmonyOS 平台上的通知。
 *
 * @see https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push#hmos
 * @implements Arrayable<string, mixed>
 */
#[ORM\Embeddable]
class HmosNotification implements Arrayable
{
    /**
     * 通知内容。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知内容'])]
    private ?string $alert = null;

    /**
     * 通知标题。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知标题'])]
    private ?string $title = null;

    /**
     * 通知类别。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知类别'])]
    private ?string $category = null;

    /**
     * 通知栏大图标。
     * 图标路径可以是以http或https开头的网络图片，如：http://www.jiguang.cn/largeIcon.jpg
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知栏大图标'])]
    private ?string $largeIcon = null;

    /**
     * 指定跳转页面。
     * 指定跳转页面（仅安卓支持），该字段为 JSON 格式。
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '指定跳转页面'])]
    private ?array $intent = null;

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
     * 测试消息。
     */
    #[ORM\Column(type: 'boolean', nullable: true, options: ['comment' => '测试消息'])]
    private ?bool $testMessage = null;

    /**
     * 回执ID。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '回执ID'])]
    private ?string $receiptId = null;

    /**
     * 扩展字段。
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '扩展字段'])]
    private ?array $extras = null;

    /**
     * 通知栏样式。
     */
    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '通知栏样式'])]
    private ?int $style = null;

    /**
     * 收件箱样式。
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '收件箱样式'])]
    private ?array $inbox = null;

    /**
     * 推送类型。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '推送类型'])]
    private ?string $pushType = null;

    /**
     * 额外数据。
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '额外数据'])]
    private ?array $extraData = null;

    public function getAlert(): ?string
    {
        return $this->alert;
    }

    public function setAlert(?string $alert): void
    {
        $this->alert = $alert;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    public function getLargeIcon(): ?string
    {
        return $this->largeIcon;
    }

    public function setLargeIcon(?string $largeIcon): void
    {
        $this->largeIcon = $largeIcon;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getIntent(): ?array
    {
        return $this->intent;
    }

    /**
     * @param array<string, mixed>|null $intent
     */
    public function setIntent(?array $intent): void
    {
        $this->intent = $intent;
    }

    public function getBadgeAddNum(): ?int
    {
        return $this->badgeAddNum;
    }

    public function setBadgeAddNum(?int $badgeAddNum): void
    {
        $this->badgeAddNum = $badgeAddNum;
    }

    public function getBadgeSetNum(): ?int
    {
        return $this->badgeSetNum;
    }

    public function setBadgeSetNum(?int $badgeSetNum): void
    {
        $this->badgeSetNum = $badgeSetNum;
    }

    public function isTestMessage(): ?bool
    {
        return $this->testMessage;
    }

    public function setTestMessage(?bool $testMessage): void
    {
        $this->testMessage = $testMessage;
    }

    public function getReceiptId(): ?string
    {
        return $this->receiptId;
    }

    public function setReceiptId(?string $receiptId): void
    {
        $this->receiptId = $receiptId;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getExtras(): ?array
    {
        return $this->extras;
    }

    /**
     * @param array<string, mixed>|null $extras
     */
    public function setExtras(?array $extras): void
    {
        $this->extras = $extras;
    }

    public function getStyle(): ?int
    {
        return $this->style;
    }

    public function setStyle(?int $style): void
    {
        $this->style = $style;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getInbox(): ?array
    {
        return $this->inbox;
    }

    /**
     * @param array<string, mixed>|null $inbox
     */
    public function setInbox(?array $inbox): void
    {
        $this->inbox = $inbox;
    }

    public function getPushType(): ?string
    {
        return $this->pushType;
    }

    public function setPushType(?string $pushType): void
    {
        $this->pushType = $pushType;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getExtraData(): ?array
    {
        return $this->extraData;
    }

    /**
     * @param array<string, mixed>|null $extraData
     */
    public function setExtraData(?array $extraData): void
    {
        $this->extraData = $extraData;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'alert' => $this->getAlert(),
            'title' => $this->getTitle(),
            'category' => $this->getCategory(),
            'large_icon' => $this->getLargeIcon(),
            'intent' => $this->getIntent(),
            'badge_add_num' => $this->getBadgeAddNum(),
            'badge_set_num' => $this->getBadgeSetNum(),
            'test_message' => $this->isTestMessage(),
            'receipt_id' => $this->getReceiptId(),
            'extras' => $this->getExtras(),
            'style' => $this->getStyle(),
            'inbox' => $this->getInbox(),
            'push_type' => $this->getPushType(),
            'extra_data' => $this->getExtraData(),
        ], fn ($value) => null !== $value);
    }
}
