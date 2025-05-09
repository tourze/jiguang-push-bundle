<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\AndroidNotification;
use JiguangPushBundle\Entity\Embedded\HmosNotification;
use JiguangPushBundle\Entity\Embedded\IosNotification;
use JiguangPushBundle\Entity\Embedded\Notification;
use JiguangPushBundle\Entity\Embedded\QuickAppNotification;
use JiguangPushBundle\Entity\Embedded\VoipNotification;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    private Notification $notification;

    protected function setUp(): void
    {
        $this->notification = new Notification();
    }

    public function testGetSetAlert(): void
    {
        $alert = '测试通知内容';
        $this->notification->setAlert($alert);
        $this->assertSame($alert, $this->notification->getAlert());
    }

    public function testGetSetAndroid(): void
    {
        $android = new AndroidNotification();
        $android->setAlert('Android通知');
        
        $this->notification->setAndroid($android);
        $this->assertSame($android, $this->notification->getAndroid());
        
        $this->notification->setAndroid(null);
        $this->assertNull($this->notification->getAndroid());
    }

    public function testGetSetIos(): void
    {
        $ios = new IosNotification();
        $ios->setAlert('iOS通知');
        
        $this->notification->setIos($ios);
        $this->assertSame($ios, $this->notification->getIos());
        
        $this->notification->setIos(null);
        $this->assertNull($this->notification->getIos());
    }

    public function testGetSetHmos(): void
    {
        $hmos = new HmosNotification();
        $hmos->setAlert('HMOS通知');
        
        $this->notification->setHmos($hmos);
        $this->assertSame($hmos, $this->notification->getHmos());
        
        $this->notification->setHmos(null);
        $this->assertNull($this->notification->getHmos());
    }

    public function testGetSetQuickapp(): void
    {
        $quickapp = new QuickAppNotification();
        $quickapp->setAlert('快应用通知');
        
        $this->notification->setQuickapp($quickapp);
        $this->assertSame($quickapp, $this->notification->getQuickapp());
        
        $this->notification->setQuickapp(null);
        $this->assertNull($this->notification->getQuickapp());
    }

    public function testGetSetVoip(): void
    {
        $voip = new VoipNotification();
        $voip->setContent(['alert' => 'VoIP通知']);
        
        $this->notification->setVoip($voip);
        $this->assertSame($voip, $this->notification->getVoip());
        
        $this->notification->setVoip(null);
        $this->assertNull($this->notification->getVoip());
    }

    public function testToArrayWithOnlyAlert(): void
    {
        $alert = '测试通知内容';
        $this->notification->setAlert($alert);
        
        $data = $this->notification->toArray();
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('alert', $data);
        $this->assertSame($alert, $data['alert']);
        
        // 平台特定通知不应存在
        $this->assertArrayNotHasKey('android', $data);
        $this->assertArrayNotHasKey('ios', $data);
        $this->assertArrayNotHasKey('hmos', $data);
        $this->assertArrayNotHasKey('quickapp', $data);
        $this->assertArrayNotHasKey('voip', $data);
    }

    public function testToArrayWithAllPlatforms(): void
    {
        $alert = '通用通知内容';
        $this->notification->setAlert($alert);
        
        // 设置Android通知
        $android = new AndroidNotification();
        $android->setAlert('Android通知');
        $android->setTitle('Android标题');
        $this->notification->setAndroid($android);
        
        // 设置iOS通知
        $ios = new IosNotification();
        $ios->setAlert('iOS通知');
        $ios->setBadge(5);
        $this->notification->setIos($ios);
        
        // 设置HMOS通知
        $hmos = new HmosNotification();
        $hmos->setAlert('HMOS通知');
        $this->notification->setHmos($hmos);
        
        // 设置快应用通知
        $quickapp = new QuickAppNotification();
        $quickapp->setAlert('快应用通知');
        $this->notification->setQuickapp($quickapp);
        
        // 设置VoIP通知
        $voip = new VoipNotification();
        $voip->setContent(['alert' => 'VoIP通知']);
        $this->notification->setVoip($voip);
        
        $data = $this->notification->toArray();
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('alert', $data);
        $this->assertArrayHasKey('android', $data);
        $this->assertArrayHasKey('ios', $data);
        $this->assertArrayHasKey('hmos', $data);
        $this->assertArrayHasKey('quickapp', $data);
        $this->assertArrayHasKey('voip', $data);
        
        $this->assertSame($alert, $data['alert']);
        
        // 验证平台特定通知内容
        $this->assertIsArray($data['android']);
        $this->assertArrayHasKey('alert', $data['android']);
        $this->assertSame('Android通知', $data['android']['alert']);
        
        $this->assertIsArray($data['ios']);
        $this->assertArrayHasKey('alert', $data['ios']);
        $this->assertSame('iOS通知', $data['ios']['alert']);
        
        $this->assertIsArray($data['hmos']);
        $this->assertArrayHasKey('alert', $data['hmos']);
        $this->assertSame('HMOS通知', $data['hmos']['alert']);
        
        $this->assertIsArray($data['quickapp']);
        $this->assertArrayHasKey('alert', $data['quickapp']);
        $this->assertSame('快应用通知', $data['quickapp']['alert']);
        
        $this->assertIsArray($data['voip']);
        if (isset($data['voip']['alert'])) {
            $this->assertSame('VoIP通知', $data['voip']['alert']);
        }
    }

    public function testToArrayOmitsNullPlatforms(): void
    {
        $alert = '通用通知内容';
        $this->notification->setAlert($alert);
        
        // 只设置部分平台通知
        $android = new AndroidNotification();
        $android->setAlert('Android通知');
        $this->notification->setAndroid($android);
        
        $ios = new IosNotification();
        $ios->setAlert('iOS通知');
        $this->notification->setIos($ios);
        
        // 其他平台保持为null
        
        $data = $this->notification->toArray();
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('alert', $data);
        $this->assertArrayHasKey('android', $data);
        $this->assertArrayHasKey('ios', $data);
        $this->assertArrayNotHasKey('hmos', $data);
        $this->assertArrayNotHasKey('quickapp', $data);
        $this->assertArrayNotHasKey('voip', $data);
        
        $this->assertSame($alert, $data['alert']);
    }
} 