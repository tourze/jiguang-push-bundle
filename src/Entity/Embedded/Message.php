<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

#[ORM\Embeddable]
class Message implements Arrayable
{
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '消息内容'])]
    private ?string $msgContent = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '消息内容类型'])]
    private ?string $contentType = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '消息标题'])]
    private ?string $title = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '扩展字段'])]
    private ?array $extras = null;

    public function getMsgContent(): ?string
    {
        return $this->msgContent;
    }

    public function setMsgContent(?string $msgContent): static
    {
        $this->msgContent = $msgContent;

        return $this;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setContentType(?string $contentType): static
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

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

    public function toArray(): array
    {
        return array_filter([
            'msg_content' => $this->msgContent,
            'content_type' => $this->contentType,
            'title' => $this->title,
            'extras' => $this->extras,
        ]);
    }
}
