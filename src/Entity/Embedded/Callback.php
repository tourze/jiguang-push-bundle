<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
#[ORM\Embeddable]
class Callback implements Arrayable
{
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '回调地址'])]
    private ?string $url = null;

    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '回调参数'])]
    private ?array $params = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '回调类型'])]
    private ?string $type = null;

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * @param array<string, mixed>|null $params
     */
    public function setParams(?array $params): void
    {
        $this->params = $params;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'url' => $this->url,
            'params' => $this->params,
            'type' => $this->type,
        ], fn ($value) => null !== $value);
    }
}
