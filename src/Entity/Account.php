<?php

namespace JiguangPushBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JiguangPushBundle\Repository\AccountRepository;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: 'jiguang_account', options: ['comment' => '极光账号配置'])]
class Account implements \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[ORM\Column(length: 100, options: ['comment' => '账号标题'])]
    private ?string $title = null;

    #[ORM\Column(length: 64, unique: true, options: ['comment' => '应用密钥'])]
    private ?string $appKey = null;

    #[ORM\Column(length: 128, options: ['comment' => '主密钥'])]
    private ?string $masterSecret = null;

    #[IndexColumn]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    private ?bool $valid = false;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppKey(): ?string
    {
        return $this->appKey;
    }

    public function setAppKey(string $appKey): static
    {
        $this->appKey = $appKey;

        return $this;
    }

    public function getMasterSecret(): ?string
    {
        return $this->masterSecret;
    }

    public function setMasterSecret(string $masterSecret): static
    {
        $this->masterSecret = $masterSecret;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title ?? 'Account #' . $this->id;
    }}
