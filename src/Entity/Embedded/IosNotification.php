<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
#[ORM\Embeddable]
class IosNotification implements Arrayable
{
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知内容'])]
    private ?string $alert = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知提示音'])]
    private ?string $sound = null;

    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '应用角标'])]
    private ?int $badge = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['comment' => '推送唤醒'])]
    private ?bool $contentAvailable = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['comment' => '通知扩展'])]
    private ?bool $mutableContent = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知类别'])]
    private ?string $category = null;

    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '扩展字段'])]
    private ?array $extras = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '线程ID'])]
    private ?string $threadId = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '中断级别'])]
    private ?string $interruptionLevel = null;

    public function getAlert(): ?string
    {
        return $this->alert;
    }

    public function setAlert(?string $alert): void
    {
        $this->alert = $alert;
    }

    public function getSound(): ?string
    {
        return $this->sound;
    }

    public function setSound(?string $sound): void
    {
        $this->sound = $sound;
    }

    public function getBadge(): ?int
    {
        return $this->badge;
    }

    public function setBadge(?int $badge): void
    {
        $this->badge = $badge;
    }

    public function isContentAvailable(): ?bool
    {
        return $this->contentAvailable;
    }

    public function setContentAvailable(?bool $contentAvailable): void
    {
        $this->contentAvailable = $contentAvailable;
    }

    public function isMutableContent(): ?bool
    {
        return $this->mutableContent;
    }

    public function setMutableContent(?bool $mutableContent): void
    {
        $this->mutableContent = $mutableContent;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): void
    {
        $this->category = $category;
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

    public function getThreadId(): ?string
    {
        return $this->threadId;
    }

    public function setThreadId(?string $threadId): void
    {
        $this->threadId = $threadId;
    }

    public function getInterruptionLevel(): ?string
    {
        return $this->interruptionLevel;
    }

    public function setInterruptionLevel(?string $interruptionLevel): void
    {
        $this->interruptionLevel = $interruptionLevel;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'alert' => $this->getAlert(),
            'sound' => $this->getSound(),
            'badge' => $this->getBadge(),
            'content-available' => $this->isContentAvailable(),
            'mutable-content' => $this->isMutableContent(),
            'category' => $this->getCategory(),
            'extras' => $this->getExtras(),
            'thread-id' => $this->getThreadId(),
            'interruption-level' => $this->getInterruptionLevel(),
        ], fn ($value) => null !== $value);
    }
}
