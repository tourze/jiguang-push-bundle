<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

#[ORM\Embeddable]
class VoipNotification implements Arrayable
{
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '通知内容'])]
    private ?array $content = null;

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function toArray(): array
    {
        return $this->content ?? [];
    }
}
