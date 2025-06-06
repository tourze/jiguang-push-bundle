<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

/**
 * Android 通知的扩展字段。
 *
 * @see https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push#android
 */
#[ORM\Embeddable]
class AndroidNotificationExtras implements Arrayable
{
    /**
     * 小米通道通知消息的长描述。
     * 长度限制：1000 个字符。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '小米通道通知消息的长描述'])]
    private ?string $mipnsContentForshort = null;

    /**
     * OPPO通道通知消息的长描述。
     * 长度限制：200 个字符。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => 'OPPO通道通知消息的长描述'])]
    private ?string $oppnsContentForshort = null;

    /**
     * vivo通道通知消息的长描述。
     * 长度限制：100 个字符。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => 'vivo通道通知消息的长描述'])]
    private ?string $vpnsContentForshort = null;

    /**
     * 魅族通道通知消息的长描述。
     * 长度限制：128 个字符。
     */
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '魅族通道通知消息的长描述'])]
    private ?string $mzpnsContentForshort = null;

    public function getMipnsContentForshort(): ?string
    {
        return $this->mipnsContentForshort;
    }

    public function setMipnsContentForshort(?string $mipnsContentForshort): static
    {
        $this->mipnsContentForshort = $mipnsContentForshort;

        return $this;
    }

    public function getOppnsContentForshort(): ?string
    {
        return $this->oppnsContentForshort;
    }

    public function setOppnsContentForshort(?string $oppnsContentForshort): static
    {
        $this->oppnsContentForshort = $oppnsContentForshort;

        return $this;
    }

    public function getVpnsContentForshort(): ?string
    {
        return $this->vpnsContentForshort;
    }

    public function setVpnsContentForshort(?string $vpnsContentForshort): static
    {
        $this->vpnsContentForshort = $vpnsContentForshort;

        return $this;
    }

    public function getMzpnsContentForshort(): ?string
    {
        return $this->mzpnsContentForshort;
    }

    public function setMzpnsContentForshort(?string $mzpnsContentForshort): static
    {
        $this->mzpnsContentForshort = $mzpnsContentForshort;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'mipns_content_forshort' => $this->mipnsContentForshort,
            'oppns_content_forshort' => $this->oppnsContentForshort,
            'vpns_content_forshort' => $this->vpnsContentForshort,
            'mzpns_content_forshort' => $this->mzpnsContentForshort,
        ]);
    }
}
