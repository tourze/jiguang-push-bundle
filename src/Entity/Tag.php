<?php

namespace JiguangPushBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JiguangPushBundle\Repository\TagRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\Table(name: 'ims_jiguang_push_tag', options: ['comment' => '标签信息'])]
#[ORM\UniqueConstraint(name: 'jiguang_push_tag_idx_uniq', columns: ['account_id', 'value'])]
class Tag implements \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '主键'])]
    private int $id = 0;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 40)]
    #[ORM\Column(length: 40, options: ['comment' => '标签值'])]
    private ?string $value = null;

    /**
     * @var Collection<int, Device>
     */
    #[ORM\ManyToMany(targetEntity: Device::class, mappedBy: 'tags', fetch: 'EXTRA_LAZY')]
    private Collection $devices;

    public function __construct()
    {
        $this->devices = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return Collection<int, Device>
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): void
    {
        if (!$this->devices->contains($device)) {
            $this->devices->add($device);
            $device->addTag($this);
        }
    }

    public function removeDevice(Device $device): void
    {
        if ($this->devices->removeElement($device)) {
            $device->removeTag($this);
        }
    }

    public function getDevicesCount(): int
    {
        return $this->devices->count();
    }

    public function __toString(): string
    {
        return $this->value ?? 'Tag #' . $this->id;
    }
}
