<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

#[ORM\Embeddable]
class QuickAppNotification implements Arrayable
{
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知内容'])]
    private ?string $alert = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知标题'])]
    private ?string $title = null;

    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '跳转页面'])]
    private ?string $page = null;

    public function getAlert(): ?string
    {
        return $this->alert;
    }

    public function setAlert(?string $alert): static
    {
        $this->alert = $alert;

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

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(?string $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'alert' => $this->alert,
            'title' => $this->title,
            'page' => $this->page,
        ]);
    }
}
