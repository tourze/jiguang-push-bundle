<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\AndroidNotification;
use JiguangPushBundle\Entity\Embedded\AndroidNotificationExtras;
use JiguangPushBundle\Enum\AlertTypeEnum;
use JiguangPushBundle\Enum\StyleEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(AndroidNotification::class)]
final class AndroidNotificationTest extends TestCase
{
    protected function createEntity(): AndroidNotification
    {
        return new AndroidNotification();
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
        yield 'alert' => ['alert', 'Test notification message'];
        yield 'title' => ['title', 'Test Title'];
        yield 'builderId' => ['builderId', 1];
        yield 'channelId' => ['channelId', 'channel-123'];
        yield 'category' => ['category', 'MESSAGE'];
        yield 'priority' => ['priority', 1];
        yield 'style' => ['style', StyleEnum::BIG_TEXT];
        yield 'alertType' => ['alertType', AlertTypeEnum::SOUND];
        yield 'bigText' => ['bigText', 'This is a big text notification'];
        yield 'inbox' => ['inbox', ['message1', 'message2', 'message3']];
        yield 'bigPicPath' => ['bigPicPath', 'https://example.com/image.jpg'];
        yield 'largeIcon' => ['largeIcon', 'https://example.com/large-icon.png'];
        yield 'smallIconUri' => ['smallIconUri', 'https://example.com/small-icon.png'];
        yield 'iconBgColor' => ['iconBgColor', '#FF0000'];
        yield 'intent' => ['intent', ['action' => 'view', 'data' => 'example://app']];
        yield 'uriActivity' => ['uriActivity', 'com.example.MainActivity'];
        yield 'uriAction' => ['uriAction', 'com.example.VIEW_ACTION'];
        yield 'badgeAddNum' => ['badgeAddNum', 1];
        yield 'badgeSetNum' => ['badgeSetNum', 5];
        yield 'badgeClass' => ['badgeClass', 'com.example.BadgeReceiver'];
        yield 'showBeginTime' => ['showBeginTime', '2024-01-01 09:00:00'];
        yield 'showEndTime' => ['showEndTime', '2024-01-01 18:00:00'];
        yield 'displayForeground' => ['displayForeground', true];
        yield 'sound' => ['sound', 'notification_sound'];
        yield 'extras' => ['extras', new AndroidNotificationExtras()];
    }

    public function testToArrayReturnsFilteredArray(): void
    {
        $notification = $this->createEntity();
        $notification->setAlert('Test message');
        $notification->setTitle('Test Title');
        $notification->setPriority(1);
        $notification->setStyle(StyleEnum::BIG_TEXT);
        $notification->setAlertType(AlertTypeEnum::SOUND);
        $notification->setDisplayForeground(true);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'alert' => 'Test message',
            'title' => 'Test Title',
            'priority' => 1,
            'style' => StyleEnum::BIG_TEXT->value,
            'alert_type' => AlertTypeEnum::SOUND->value,
            'display_foreground' => true,
        ], $result);
    }

    public function testToArrayExcludesNullValues(): void
    {
        $notification = $this->createEntity();
        $notification->setAlert('Test message');
        $notification->setTitle(null);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'alert' => 'Test message',
        ], $result);
        $this->assertArrayNotHasKey('title', $result);
    }

    public function testToArrayReturnsEmptyArrayWhenAllNull(): void
    {
        $notification = $this->createEntity();

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testToArrayIncludesExtrasWhenSet(): void
    {
        $notification = $this->createEntity();
        $extras = new AndroidNotificationExtras();
        $extras->setMipnsContentForshort('小米长描述');
        $extras->setOppnsContentForshort('OPPO长描述');
        $notification->setExtras($extras);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('extras', $result);
        $this->assertEquals([
            'mipns_content_forshort' => '小米长描述',
            'oppns_content_forshort' => 'OPPO长描述',
        ], $result['extras']);
    }

    public function testEnumValuesAreConvertedToStrings(): void
    {
        $notification = $this->createEntity();
        $notification->setStyle(StyleEnum::INBOX);
        $notification->setAlertType(AlertTypeEnum::VIBRATE);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals(StyleEnum::INBOX->value, $result['style']);
        $this->assertEquals(AlertTypeEnum::VIBRATE->value, $result['alert_type']);
    }

    public function testBooleanPropertiesHandledCorrectly(): void
    {
        $notification = $this->createEntity();
        $notification->setDisplayForeground(true);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertTrue($result['display_foreground']);
    }
}
