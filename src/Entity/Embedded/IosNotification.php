<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

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

    public function setAlert(?string $alert): static
    {
        $this->alert = $alert;

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

    public function getBadge(): ?int
    {
        return $this->badge;
    }

    public function setBadge(?int $badge): static
    {
        $this->badge = $badge;

        return $this;
    }

    public function isContentAvailable(): ?bool
    {
        return $this->contentAvailable;
    }

    public function setContentAvailable(?bool $contentAvailable): static
    {
        $this->contentAvailable = $contentAvailable;

        return $this;
    }

    public function isMutableContent(): ?bool
    {
        return $this->mutableContent;
    }

    public function setMutableContent(?bool $mutableContent): static
    {
        $this->mutableContent = $mutableContent;

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

    public function getExtras(): ?array
    {
        return $this->extras;
    }

    public function setExtras(?array $extras): static
    {
        $this->extras = $extras;

        return $this;
    }

    public function getThreadId(): ?string
    {
        return $this->threadId;
    }

    public function setThreadId(?string $threadId): static
    {
        $this->threadId = $threadId;

        return $this;
    }

    public function getInterruptionLevel(): ?string
    {
        return $this->interruptionLevel;
    }

    public function setInterruptionLevel(?string $interruptionLevel): static
    {
        $this->interruptionLevel = $interruptionLevel;

        return $this;
    }

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
        ]);
    }
}
