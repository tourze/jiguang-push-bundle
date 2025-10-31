<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
#[ORM\Embeddable]
class Options implements Arrayable
{
    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '离线消息保留时长'])]
    private ?int $timeToLive = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['comment' => '是否生产环境'])]
    private ?bool $apnsProduction = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => 'APNs折叠ID'])]
    private ?string $apnsCollapseId = null;

    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '第三方渠道配置'])]
    private ?array $thirdPartyChannel = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['comment' => '是否覆盖'])]
    private ?bool $override = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '唯一标识'])]
    private ?string $uniqueKey = null;

    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '大推送时长'])]
    private ?int $bigPushDuration = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['comment' => '是否可撤回'])]
    private ?bool $withdrawable = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['comment' => '是否启用第三方渠道'])]
    private ?bool $thirdPartyEnable = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['comment' => '是否可缓存'])]
    private ?bool $cacheable = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['comment' => '是否同步'])]
    private ?bool $sync = null;

    public function getTimeToLive(): ?int
    {
        return $this->timeToLive;
    }

    public function setTimeToLive(?int $timeToLive): void
    {
        $this->timeToLive = $timeToLive;
    }

    public function isApnsProduction(): ?bool
    {
        return $this->apnsProduction;
    }

    public function setApnsProduction(?bool $apnsProduction): void
    {
        $this->apnsProduction = $apnsProduction;
    }

    public function getApnsCollapseId(): ?string
    {
        return $this->apnsCollapseId;
    }

    public function setApnsCollapseId(?string $apnsCollapseId): void
    {
        $this->apnsCollapseId = $apnsCollapseId;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getThirdPartyChannel(): ?array
    {
        return $this->thirdPartyChannel;
    }

    /**
     * @param array<string, mixed>|null $thirdPartyChannel
     */
    public function setThirdPartyChannel(?array $thirdPartyChannel): void
    {
        $this->thirdPartyChannel = $thirdPartyChannel;
    }

    public function isOverride(): ?bool
    {
        return $this->override;
    }

    public function setOverride(?bool $override): void
    {
        $this->override = $override;
    }

    public function getUniqueKey(): ?string
    {
        return $this->uniqueKey;
    }

    public function setUniqueKey(?string $uniqueKey): void
    {
        $this->uniqueKey = $uniqueKey;
    }

    public function getBigPushDuration(): ?int
    {
        return $this->bigPushDuration;
    }

    public function setBigPushDuration(?int $bigPushDuration): void
    {
        $this->bigPushDuration = $bigPushDuration;
    }

    public function isWithdrawable(): ?bool
    {
        return $this->withdrawable;
    }

    public function setWithdrawable(?bool $withdrawable): void
    {
        $this->withdrawable = $withdrawable;
    }

    public function isThirdPartyEnable(): ?bool
    {
        return $this->thirdPartyEnable;
    }

    public function setThirdPartyEnable(?bool $thirdPartyEnable): void
    {
        $this->thirdPartyEnable = $thirdPartyEnable;
    }

    public function isCacheable(): ?bool
    {
        return $this->cacheable;
    }

    public function setCacheable(?bool $cacheable): void
    {
        $this->cacheable = $cacheable;
    }

    public function isSync(): ?bool
    {
        return $this->sync;
    }

    public function setSync(?bool $sync): void
    {
        $this->sync = $sync;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'time_to_live' => $this->timeToLive,
            'apns_production' => $this->apnsProduction,
            'apns_collapse_id' => $this->apnsCollapseId,
            'third_party_channel' => $this->thirdPartyChannel,
            'override' => $this->override,
            'unique_key' => $this->uniqueKey,
            'big_push_duration' => $this->bigPushDuration,
            'withdrawable' => $this->withdrawable,
            'third_party_enable' => $this->thirdPartyEnable,
            'cacheable' => $this->cacheable,
            'sync' => $this->sync,
        ], fn ($value) => null !== $value);
    }
}
