<?php

namespace JiguangPushBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
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

    public function setAlert(?string $alert): void
    {
        $this->alert = $alert;
    }

    public function getAndroid(): ?AndroidNotification
    {
        return $this->android;
    }

    public function setAndroid(?AndroidNotification $android): void
    {
        $this->android = $android;
    }

    public function getIos(): ?IosNotification
    {
        return $this->ios;
    }

    public function setIos(?IosNotification $ios): void
    {
        $this->ios = $ios;
    }

    public function getHmos(): ?HmosNotification
    {
        return $this->hmos;
    }

    public function setHmos(?HmosNotification $hmos): void
    {
        $this->hmos = $hmos;
    }

    public function getQuickapp(): ?QuickAppNotification
    {
        return $this->quickapp;
    }

    public function setQuickapp(?QuickAppNotification $quickapp): void
    {
        $this->quickapp = $quickapp;
    }

    public function getVoip(): ?VoipNotification
    {
        return $this->voip;
    }

    public function setVoip(?VoipNotification $voip): void
    {
        $this->voip = $voip;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if (null !== $this->alert) {
            $data['alert'] = $this->alert;
        }

        if (null !== $this->android) {
            $data['android'] = $this->android->toArray();
        }

        if (null !== $this->ios) {
            $data['ios'] = $this->ios->toArray();
        }

        if (null !== $this->hmos) {
            $data['hmos'] = $this->hmos->toArray();
        }

        if (null !== $this->quickapp) {
            $data['quickapp'] = $this->quickapp->toArray();
        }

        if (null !== $this->voip) {
            $data['voip'] = $this->voip->toArray();
        }

        return $data;
    }
}
