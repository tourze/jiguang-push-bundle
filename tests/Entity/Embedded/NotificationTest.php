<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\AndroidNotification;
use JiguangPushBundle\Entity\Embedded\HmosNotification;
use JiguangPushBundle\Entity\Embedded\IosNotification;
use JiguangPushBundle\Entity\Embedded\Notification;
use JiguangPushBundle\Entity\Embedded\QuickAppNotification;
use JiguangPushBundle\Entity\Embedded\VoipNotification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Notification::class)]
final class NotificationTest extends TestCase
{
    protected function createEntity(): Notification
    {
        return new Notification();
    }

    #[DataProvider('propertiesProvider')]
    public function testGettersAndSetters(string $property, mixed $value): void
    {
        $entity = $this->createEntity();
        $setter = 'set' . ucfirst($property);
        $getter = 'get' . ucfirst($property);
        $isGetter = 'is' . ucfirst($property);

        $this->assertTrue(method_exists($entity, $setter), "Setter {$setter} does not exist");

        // Check if it's a boolean getter (is* method) or regular getter (get* method)
        if (method_exists($entity, $isGetter)) {
            $getter = $isGetter;
        }

        $this->assertTrue(method_exists($entity, $getter), "Getter {$getter} does not exist");

        $setterCallable = [$entity, $setter];
        self::assertIsCallable($setterCallable);
        call_user_func($setterCallable, $value);

        $getterCallable = [$entity, $getter];
        self::assertIsCallable($getterCallable);
        $this->assertSame($value, call_user_func($getterCallable), 'Getter should return the set value');
    }

    public static function propertiesProvider(): \Generator
    {
        yield 'alert' => ['alert', 'Test notification alert'];
        yield 'android' => ['android', new AndroidNotification()];
        yield 'ios' => ['ios', new IosNotification()];
        yield 'hmos' => ['hmos', new HmosNotification()];
        yield 'quickapp' => ['quickapp', new QuickAppNotification()];
        yield 'voip' => ['voip', new VoipNotification()];
    }

    public function testToArrayReturnsAlertOnly(): void
    {
        $notification = $this->createEntity();
        $notification->setAlert('Test alert');

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals(['alert' => 'Test alert'], $result);
    }

    public function testToArrayReturnsEmptyArrayWhenAllNull(): void
    {
        $notification = $this->createEntity();

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testToArrayIncludesAndroidNotification(): void
    {
        $notification = $this->createEntity();
        $android = new AndroidNotification();
        $android->setTitle('Android Title');
        $android->setAlert('Android Alert');
        $notification->setAndroid($android);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('android', $result);
        $this->assertEquals([
            'alert' => 'Android Alert',
            'title' => 'Android Title',
        ], $result['android']);
    }

    public function testToArrayIncludesIosNotification(): void
    {
        $notification = $this->createEntity();
        $ios = new IosNotification();
        $ios->setAlert('iOS Alert');
        $ios->setSound('default');
        $notification->setIos($ios);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('ios', $result);
        $this->assertEquals([
            'alert' => 'iOS Alert',
            'sound' => 'default',
        ], $result['ios']);
    }

    public function testToArrayIncludesHmosNotification(): void
    {
        $notification = $this->createEntity();
        $hmos = new HmosNotification();
        $hmos->setTitle('HarmonyOS Title');
        $hmos->setTestMessage(true);
        $notification->setHmos($hmos);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('hmos', $result);
        $this->assertEquals([
            'title' => 'HarmonyOS Title',
            'test_message' => true,
        ], $result['hmos']);
    }

    public function testToArrayIncludesQuickAppNotification(): void
    {
        $notification = $this->createEntity();
        $quickapp = new QuickAppNotification();
        $quickapp->setTitle('QuickApp Title');
        $notification->setQuickapp($quickapp);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('quickapp', $result);
        $this->assertEquals([
            'title' => 'QuickApp Title',
        ], $result['quickapp']);
    }

    public function testToArrayIncludesVoipNotification(): void
    {
        $notification = $this->createEntity();
        $voip = new VoipNotification();
        $voip->setContent(['title' => 'VoIP Title', 'sound' => 'ringtone.caf']);
        $notification->setVoip($voip);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('voip', $result);
        $this->assertEquals([
            'title' => 'VoIP Title',
            'sound' => 'ringtone.caf',
        ], $result['voip']);
    }

    public function testToArrayCombinesAlertAndPlatformNotifications(): void
    {
        $notification = $this->createEntity();
        $notification->setAlert('Global Alert');

        $android = new AndroidNotification();
        $android->setTitle('Android Title');
        $notification->setAndroid($android);

        $ios = new IosNotification();
        $ios->setSound('default');
        $notification->setIos($ios);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'alert' => 'Global Alert',
            'android' => [
                'title' => 'Android Title',
            ],
            'ios' => [
                'sound' => 'default',
            ],
        ], $result);
    }

    public function testSettersWork(): void
    {
        $notification = $this->createEntity();

        $notification->setAlert('Test Alert');
        $notification->setAndroid(new AndroidNotification());
        $notification->setIos(new IosNotification());

        $this->assertEquals('Test Alert', $notification->getAlert());
        $this->assertInstanceOf(AndroidNotification::class, $notification->getAndroid());
        $this->assertInstanceOf(IosNotification::class, $notification->getIos());
    }

    public function testNullValueHandling(): void
    {
        $notification = $this->createEntity();

        $this->assertNull($notification->getAlert());
        $this->assertNull($notification->getAndroid());
        $this->assertNull($notification->getIos());
        $this->assertNull($notification->getHmos());
        $this->assertNull($notification->getQuickapp());
        $this->assertNull($notification->getVoip());
    }
}
