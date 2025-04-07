<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

/**
 * 推送目标
 */
#[ORM\Embeddable]
class Audience implements Arrayable
{
    #[ORM\Column(type: 'boolean', options: ['comment' => '是否推送给所有用户'])]
    private bool $all = false;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '标签'])]
    private ?array $tag = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '标签AND'])]
    private ?array $tagAnd = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '标签NOT'])]
    private ?array $tagNot = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '别名'])]
    private ?array $alias = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '注册ID'])]
    private ?array $registrationId = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '用户分群 ID'])]
    private ?array $segment = null;

    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => 'A/B Test ID'])]
    private ?array $abTest = null;

    public function isAll(): bool
    {
        return $this->all;
    }

    public function setAll(bool $all): static
    {
        $this->all = $all;

        return $this;
    }

    public function getTag(): ?array
    {
        return $this->tag;
    }

    public function setTag(?array $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    public function getTagAnd(): ?array
    {
        return $this->tagAnd;
    }

    public function setTagAnd(?array $tagAnd): static
    {
        $this->tagAnd = $tagAnd;

        return $this;
    }

    public function getTagNot(): ?array
    {
        return $this->tagNot;
    }

    public function setTagNot(?array $tagNot): static
    {
        $this->tagNot = $tagNot;

        return $this;
    }

    public function getAlias(): ?array
    {
        return $this->alias;
    }

    public function setAlias(?array $alias): static
    {
        $this->alias = $alias;

        return $this;
    }

    public function getRegistrationId(): ?array
    {
        return $this->registrationId;
    }

    public function setRegistrationId(?array $registrationId): static
    {
        $this->registrationId = $registrationId;

        return $this;
    }

    public function getSegment(): ?array
    {
        return $this->segment;
    }

    public function setSegment(?array $segment): static
    {
        $this->segment = $segment;

        return $this;
    }

    public function getAbTest(): ?array
    {
        return $this->abTest;
    }

    public function setAbTest(?array $abTest): static
    {
        $this->abTest = $abTest;

        return $this;
    }

    public function toData(): string|array
    {
        if ($this->isAll()) {
            return 'all';
        }

        return array_filter([
            'tag' => $this->getTag(),
            'tag_and' => $this->getTagAnd(),
            'tag_not' => $this->getTagNot(),
            'alias' => $this->getAlias(),
            'registration_id' => $this->getRegistrationId(),
            'segment' => $this->getSegment(),
            'abtest' => $this->getAbTest(),
        ]);
    }

    public function toArray(): array
    {
        return is_array($this->toData()) ? $this->toData() : ['all' => true];
    }
}
