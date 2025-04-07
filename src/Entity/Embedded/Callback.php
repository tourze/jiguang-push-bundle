<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

#[ORM\Embeddable]
class Callback implements Arrayable
{
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '回调地址'])]
    private ?string $url = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '回调参数'])]
    private ?array $params = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '回调类型'])]
    private ?string $type = null;

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getParams(): ?array
    {
        return $this->params;
    }

    public function setParams(?array $params): static
    {
        $this->params = $params;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'url' => $this->url,
            'params' => $this->params,
            'type' => $this->type,
        ]);
    }
}
