<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

#[ORM\Embeddable]
class Notification implements Arrayable
{
    #[ORM\Column(type: 'string', nullable: true, options: ['comment' => '通知内容'])]
    private ?string $alert = null;

    #[ORM\Embedded(class: AndroidNotification::class)]
    private ?AndroidNotification $android = null;

    #[ORM\Embedded(class: IosNotification::class)]
    private ?IosNotification $ios = null;

    #[ORM\Embedded(class: HmosNotification::class)]
    private ?HmosNotification $hmos = null;

    #[ORM\Embedded(class: QuickAppNotification::class)]
    private ?QuickAppNotification $quickapp = null;

    #[ORM\Embedded(class: VoipNotification::class)]
    private ?VoipNotification $voip = null;

    public function getAlert(): ?string
    {
        return $this->alert;
    }

    public function setAlert(?string $alert): static
    {
        $this->alert = $alert;

        return $this;
    }

    public function getAndroid(): ?AndroidNotification
    {
        return $this->android;
    }

    public function setAndroid(?AndroidNotification $android): static
    {
        $this->android = $android;

        return $this;
    }

    public function getIos(): ?IosNotification
    {
        return $this->ios;
    }

    public function setIos(?IosNotification $ios): static
    {
        $this->ios = $ios;

        return $this;
    }

    public function getHmos(): ?HmosNotification
    {
        return $this->hmos;
    }

    public function setHmos(?HmosNotification $hmos): static
    {
        $this->hmos = $hmos;

        return $this;
    }

    public function getQuickapp(): ?QuickAppNotification
    {
        return $this->quickapp;
    }

    public function setQuickapp(?QuickAppNotification $quickapp): static
    {
        $this->quickapp = $quickapp;

        return $this;
    }

    public function getVoip(): ?VoipNotification
    {
        return $this->voip;
    }

    public function setVoip(?VoipNotification $voip): static
    {
        $this->voip = $voip;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->alert !== null) {
            $data['alert'] = $this->alert;
        }

        if ($this->android !== null) {
            $data['android'] = $this->android->toArray();
        }

        if ($this->ios !== null) {
            $data['ios'] = $this->ios->toArray();
        }

        if ($this->hmos !== null) {
            $data['hmos'] = $this->hmos->toArray();
        }

        if ($this->quickapp !== null) {
            $data['quickapp'] = $this->quickapp->toArray();
        }

        if ($this->voip !== null) {
            $data['voip'] = $this->voip->toArray();
        }

        return $data;
    }
}
