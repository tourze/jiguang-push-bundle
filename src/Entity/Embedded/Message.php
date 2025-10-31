<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
#[ORM\Embeddable]
class Message implements Arrayable
{
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '消息内容'])]
    private ?string $msgContent = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '消息内容类型'])]
    private ?string $contentType = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '消息标题'])]
    private ?string $title = null;

    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '扩展字段'])]
    private ?array $extras = null;

    public function getMsgContent(): ?string
    {
        return $this->msgContent;
    }

    public function setMsgContent(?string $msgContent): void
    {
        $this->msgContent = $msgContent;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setContentType(?string $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getExtras(): ?array
    {
        return $this->extras;
    }

    /**
     * @param array<string, mixed>|null $extras
     */
    public function setExtras(?array $extras): void
    {
        $this->extras = $extras;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'msg_content' => $this->msgContent,
            'content_type' => $this->contentType,
            'title' => $this->title,
            'extras' => $this->extras,
        ], fn ($value) => null !== $value);
    }
}
