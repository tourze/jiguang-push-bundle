<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class LiveActivity
{
    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '活动ID'])]
    private ?string $activityId = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '推送令牌'])]
    private ?string $pushToken = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '事件状态'])]
    private ?array $event = null;

    #[ORM\Column(nullable: true, options: ['comment' => '过期时间戳'])]
    private ?int $timestamp = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '活动内容'])]
    private ?array $contentState = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '推送配置'])]
    private ?array $staleDate = null;

    #[ORM\Column(nullable: true, options: ['comment' => '优先级'])]
    private ?int $relevanceScore = null;

    public function getActivityId(): ?string
    {
        return $this->activityId;
    }

    public function setActivityId(?string $activityId): static
    {
        $this->activityId = $activityId;

        return $this;
    }

    public function getPushToken(): ?string
    {
        return $this->pushToken;
    }

    public function setPushToken(?string $pushToken): static
    {
        $this->pushToken = $pushToken;

        return $this;
    }

    public function getEvent(): ?array
    {
        return $this->event;
    }

    public function setEvent(?array $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function setTimestamp(?int $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getContentState(): ?array
    {
        return $this->contentState;
    }

    public function setContentState(?array $contentState): static
    {
        $this->contentState = $contentState;

        return $this;
    }

    public function getStaleDate(): ?array
    {
        return $this->staleDate;
    }

    public function setStaleDate(?array $staleDate): static
    {
        $this->staleDate = $staleDate;

        return $this;
    }

    public function getRelevanceScore(): ?int
    {
        return $this->relevanceScore;
    }

    public function setRelevanceScore(?int $relevanceScore): static
    {
        $this->relevanceScore = $relevanceScore;

        return $this;
    }
}
