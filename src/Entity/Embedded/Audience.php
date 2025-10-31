<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

/**
 * 推送目标
 * @implements Arrayable<string, mixed>
 */
#[ORM\Embeddable]
class Audience implements Arrayable
{
    #[ORM\Column(type: 'boolean', options: ['comment' => '是否推送给所有用户'])]
    private bool $all = false;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '标签'])]
    private ?array $tag = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '标签AND'])]
    private ?array $tagAnd = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '标签NOT'])]
    private ?array $tagNot = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '别名'])]
    private ?array $alias = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '注册ID'])]
    private ?array $registrationId = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => '用户分群 ID'])]
    private ?array $segment = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: 'json', nullable: true, options: ['comment' => 'A/B Test ID'])]
    private ?array $abTest = null;

    public function isAll(): bool
    {
        return $this->all;
    }

    public function setAll(bool $all): void
    {
        $this->all = $all;
    }

    /**
     * @return array<string>|null
     */
    public function getTag(): ?array
    {
        return $this->tag;
    }

    /**
     * @param array<string>|null $tag
     */
    public function setTag(?array $tag): void
    {
        $this->tag = $tag;
    }

    /**
     * @return array<string>|null
     */
    public function getTagAnd(): ?array
    {
        return $this->tagAnd;
    }

    /**
     * @param array<string>|null $tagAnd
     */
    public function setTagAnd(?array $tagAnd): void
    {
        $this->tagAnd = $tagAnd;
    }

    /**
     * @return array<string>|null
     */
    public function getTagNot(): ?array
    {
        return $this->tagNot;
    }

    /**
     * @param array<string>|null $tagNot
     */
    public function setTagNot(?array $tagNot): void
    {
        $this->tagNot = $tagNot;
    }

    /**
     * @return array<string>|null
     */
    public function getAlias(): ?array
    {
        return $this->alias;
    }

    /**
     * @param array<string>|null $alias
     */
    public function setAlias(?array $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return array<string>|null
     */
    public function getRegistrationId(): ?array
    {
        return $this->registrationId;
    }

    /**
     * @param array<string>|null $registrationId
     */
    public function setRegistrationId(?array $registrationId): void
    {
        $this->registrationId = $registrationId;
    }

    /**
     * @return array<string>|null
     */
    public function getSegment(): ?array
    {
        return $this->segment;
    }

    /**
     * @param array<string>|null $segment
     */
    public function setSegment(?array $segment): void
    {
        $this->segment = $segment;
    }

    /**
     * @return array<string>|null
     */
    public function getAbTest(): ?array
    {
        return $this->abTest;
    }

    /**
     * @param array<string>|null $abTest
     */
    public function setAbTest(?array $abTest): void
    {
        $this->abTest = $abTest;
    }

    /**
     * @return string|array<string, mixed>
     */
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
        ], fn ($value) => null !== $value);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return is_array($this->toData()) ? $this->toData() : ['all' => true];
    }
}
