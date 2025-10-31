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

    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '事件状态'])]
    private ?array $event = null;

    #[ORM\Column(nullable: true, options: ['comment' => '过期时间戳'])]
    private ?int $timestamp = null;

    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '活动内容'])]
    private ?array $contentState = null;

    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '推送配置'])]
    private ?array $staleDate = null;

    #[ORM\Column(nullable: true, options: ['comment' => '优先级'])]
    private ?int $relevanceScore = null;

    public function getActivityId(): ?string
    {
        return $this->activityId;
    }

    public function setActivityId(?string $activityId): void
    {
        $this->activityId = $activityId;
    }

    public function getPushToken(): ?string
    {
        return $this->pushToken;
    }

    public function setPushToken(?string $pushToken): void
    {
        $this->pushToken = $pushToken;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getEvent(): ?array
    {
        return $this->event;
    }

    /**
     * @param array<string, mixed>|null $event
     */
    public function setEvent(?array $event): void
    {
        $this->event = $event;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function setTimestamp(?int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getContentState(): ?array
    {
        return $this->contentState;
    }

    /**
     * @param array<string, mixed>|null $contentState
     */
    public function setContentState(?array $contentState): void
    {
        $this->contentState = $contentState;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getStaleDate(): ?array
    {
        return $this->staleDate;
    }

    /**
     * @param array<string, mixed>|null $staleDate
     */
    public function setStaleDate(?array $staleDate): void
    {
        $this->staleDate = $staleDate;
    }

    public function getRelevanceScore(): ?int
    {
        return $this->relevanceScore;
    }

    public function setRelevanceScore(?int $relevanceScore): void
    {
        $this->relevanceScore = $relevanceScore;
    }
}
