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
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\Arrayable;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

/**
 * @see https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push
 * @implements Arrayable<string, mixed>
 */
#[ORM\Entity(repositoryClass: PushRepository::class)]
#[ORM\Table(name: 'ims_jiguang_push_request', options: ['comment' => '推送消息'])]
class Push implements Arrayable, \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Account $account;

    #[Assert\NotNull]
    #[Assert\Choice(callback: [PlatformEnum::class, 'cases'])]
    #[ORM\Column(type: Types::STRING, enumType: PlatformEnum::class, options: ['comment' => '推送平台'])]
    private PlatformEnum $platform;

    #[Assert\Valid]
    #[ORM\Embedded(class: Audience::class)]
    private Audience $audience;

    #[Assert\Valid]
    #[ORM\Embedded(class: Notification::class)]
    private ?Notification $notification = null;

    #[Assert\Valid]
    #[ORM\Embedded(class: Message::class)]
    private ?Message $message = null;

    #[Assert\Valid]
    #[ORM\Embedded(class: Options::class)]
    private ?Options $options = null;

    #[Assert\Valid]
    #[ORM\Embedded(class: Callback::class)]
    private ?Callback $callback = null;

    #[Assert\Length(max: 100)]
    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '推送ID'])]
    private ?string $cid = null;

    #[Assert\Length(max: 100)]
    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '消息ID，发送成功后返回'])]
    private ?string $msgId = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }

    public function getPlatform(): PlatformEnum
    {
        return $this->platform;
    }

    public function setPlatform(PlatformEnum $platform): void
    {
        $this->platform = $platform;
    }

    public function getAudience(): Audience
    {
        return $this->audience;
    }

    public function setAudience(Audience $audience): void
    {
        $this->audience = $audience;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): void
    {
        $this->notification = $notification;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): void
    {
        $this->message = $message;
    }

    public function getOptions(): ?Options
    {
        return $this->options;
    }

    public function setOptions(?Options $options): void
    {
        $this->options = $options;
    }

    public function getCallback(): ?Callback
    {
        return $this->callback;
    }

    public function setCallback(?Callback $callback): void
    {
        $this->callback = $callback;
    }

    public function getCid(): ?string
    {
        return $this->cid;
    }

    public function setCid(?string $cid): void
    {
        $this->cid = $cid;
    }

    public function getMsgId(): ?string
    {
        return $this->msgId;
    }

    public function setMsgId(?string $msgId): void
    {
        $this->msgId = $msgId;
    }

    /**
     * 转换为极光推送API请求数据结构
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'platform' => $this->getPlatform()->value,
            'audience' => $this->getAudience()->toData(),
        ];

        if (null !== $this->getNotification()) {
            $data['notification'] = $this->getNotification()->toArray();
        }

        if (null !== $this->getMessage()) {
            $data['message'] = $this->getMessage()->toArray();
        }

        if (null !== $this->getOptions()) {
            $data['options'] = $this->getOptions()->toArray();
        }

        if (null !== $this->getCallback()) {
            $data['callback'] = $this->getCallback()->toArray();
        }

        if (null !== $this->getCid()) {
            $data['cid'] = $this->getCid();
        }

        // if ($this->getMsgId() !== null) {
        //     $data['msgId'] = $this->getMsgId();
        // }

        return array_filter($data, fn ($value) => '' !== $value && [] !== $value);
    }

    public function __toString(): string
    {
        return 'Push #' . $this->id . ' - ' . $this->platform->value;
    }
}
