<?php

namespace JiguangPushBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JiguangPushBundle\Entity\Embedded\Audience;
use JiguangPushBundle\Entity\Embedded\Callback;
use JiguangPushBundle\Entity\Embedded\Message;
use JiguangPushBundle\Entity\Embedded\Notification;
use JiguangPushBundle\Entity\Embedded\Options;
use JiguangPushBundle\Enum\PlatformEnum;
use JiguangPushBundle\Repository\PushRepository;
use Tourze\Arrayable\Arrayable;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

/**
 * @see https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push
 */
#[ORM\Entity(repositoryClass: PushRepository::class)]
#[ORM\Table(name: 'ims_jiguang_push_request', options: ['comment' => '推送消息'])]
class Push implements Arrayable
{
    use TimestampableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Account $account;

    #[ORM\Column(type: 'string', enumType: PlatformEnum::class, options: ['comment' => '推送平台'])]
    private PlatformEnum $platform;

    #[ORM\Embedded(class: Audience::class)]
    private Audience $audience;

    #[ORM\Embedded(class: Notification::class)]
    private ?Notification $notification = null;

    #[ORM\Embedded(class: Message::class)]
    private ?Message $message = null;

    #[ORM\Embedded(class: Options::class)]
    private ?Options $options = null;

    #[ORM\Embedded(class: Callback::class)]
    private ?Callback $callback = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '推送ID'])]
    private ?string $cid = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '消息ID，发送成功后返回'])]
    private ?string $msgId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getPlatform(): PlatformEnum
    {
        return $this->platform;
    }

    public function setPlatform(PlatformEnum $platform): static
    {
        $this->platform = $platform;

        return $this;
    }

    public function getAudience(): Audience
    {
        return $this->audience;
    }

    public function setAudience(Audience $audience): static
    {
        $this->audience = $audience;

        return $this;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): static
    {
        $this->notification = $notification;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getOptions(): ?Options
    {
        return $this->options;
    }

    public function setOptions(?Options $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getCallback(): ?Callback
    {
        return $this->callback;
    }

    public function setCallback(?Callback $callback): static
    {
        $this->callback = $callback;

        return $this;
    }

    public function getCid(): ?string
    {
        return $this->cid;
    }

    public function setCid(?string $cid): static
    {
        $this->cid = $cid;

        return $this;
    }

    public function getMsgId(): ?string
    {
        return $this->msgId;
    }

    public function setMsgId(?string $msgId): static
    {
        $this->msgId = $msgId;

        return $this;
    }

    /**
     * 转换为极光推送API请求数据结构
     */
    public function toArray(): array
    {
        $data = [
            'platform' => $this->getPlatform()->value,
            'audience' => $this->getAudience()->toData(),
        ];

        if ($this->getNotification() !== null) {
            $data['notification'] = $this->getNotification()->toArray();
        }

        if ($this->getMessage() !== null) {
            $data['message'] = $this->getMessage()->toArray();
        }

        if ($this->getOptions() !== null) {
            $data['options'] = $this->getOptions()->toArray();
        }

        if ($this->getCallback() !== null) {
            $data['callback'] = $this->getCallback()->toArray();
        }

        if ($this->getCid() !== null) {
            $data['cid'] = $this->getCid();
        }

        // if ($this->getMsgId() !== null) {
        //     $data['msgId'] = $this->getMsgId();
        // }

        return array_filter($data);
    }}
