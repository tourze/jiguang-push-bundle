<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
#[ORM\Embeddable]
class VoipNotification implements Arrayable
{
    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '通知内容'])]
    private ?array $content = null;

    /**
     * @return array<string, mixed>|null
     */
    public function getContent(): ?array
    {
        return $this->content;
    }

    /**
     * @param array<string, mixed>|null $content
     */
    public function setContent(?array $content): void
    {
        $this->content = $content;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->content ?? [];
    }
}
