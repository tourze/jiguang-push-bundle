<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

#[ORM\Embeddable]
class Options implements Arrayable
{
    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '离线消息保留时长'])]
    private ?int $timeToLive = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['comment' => '是否生产环境'])]
    private ?bool $apnsProduction = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => 'APNs折叠ID'])]
    private ?string $apnsCollapseId = null;

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

    public function setTimeToLive(?int $timeToLive): static
    {
        $this->timeToLive = $timeToLive;

        return $this;
    }

    public function isApnsProduction(): ?bool
    {
        return $this->apnsProduction;
    }

    public function setApnsProduction(?bool $apnsProduction): static
    {
        $this->apnsProduction = $apnsProduction;

        return $this;
    }

    public function getApnsCollapseId(): ?string
    {
        return $this->apnsCollapseId;
    }

    public function setApnsCollapseId(?string $apnsCollapseId): static
    {
        $this->apnsCollapseId = $apnsCollapseId;

        return $this;
    }

    public function getThirdPartyChannel(): ?array
    {
        return $this->thirdPartyChannel;
    }

    public function setThirdPartyChannel(?array $thirdPartyChannel): static
    {
        $this->thirdPartyChannel = $thirdPartyChannel;

        return $this;
    }

    public function isOverride(): ?bool
    {
        return $this->override;
    }

    public function setOverride(?bool $override): static
    {
        $this->override = $override;

        return $this;
    }

    public function getUniqueKey(): ?string
    {
        return $this->uniqueKey;
    }

    public function setUniqueKey(?string $uniqueKey): static
    {
        $this->uniqueKey = $uniqueKey;

        return $this;
    }

    public function getBigPushDuration(): ?int
    {
        return $this->bigPushDuration;
    }

    public function setBigPushDuration(?int $bigPushDuration): static
    {
        $this->bigPushDuration = $bigPushDuration;

        return $this;
    }

    public function isWithdrawable(): ?bool
    {
        return $this->withdrawable;
    }

    public function setWithdrawable(?bool $withdrawable): static
    {
        $this->withdrawable = $withdrawable;

        return $this;
    }

    public function isThirdPartyEnable(): ?bool
    {
        return $this->thirdPartyEnable;
    }

    public function setThirdPartyEnable(?bool $thirdPartyEnable): static
    {
        $this->thirdPartyEnable = $thirdPartyEnable;

        return $this;
    }

    public function isCacheable(): ?bool
    {
        return $this->cacheable;
    }

    public function setCacheable(?bool $cacheable): static
    {
        $this->cacheable = $cacheable;

        return $this;
    }

    public function isSync(): ?bool
    {
        return $this->sync;
    }

    public function setSync(?bool $sync): static
    {
        $this->sync = $sync;

        return $this;
    }

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
        ]);
    }
}
