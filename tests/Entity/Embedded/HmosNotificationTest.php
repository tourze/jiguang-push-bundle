<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\HmosNotification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(HmosNotification::class)]
final class HmosNotificationTest extends TestCase
{
    protected function createEntity(): HmosNotification
    {
        return new HmosNotification();
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
        yield 'category' => ['category', 'MESSAGE'];
        yield 'largeIcon' => ['largeIcon', 'https://example.com/large-icon.png'];
        yield 'intent' => ['intent', ['action' => 'view', 'data' => 'example://app']];
        yield 'badgeAddNum' => ['badgeAddNum', 1];
        yield 'badgeSetNum' => ['badgeSetNum', 5];
        yield 'testMessage' => ['testMessage', true];
        yield 'receiptId' => ['receiptId', 'receipt-123'];
        yield 'extras' => ['extras', ['key1' => 'value1', 'key2' => 'value2']];
        yield 'style' => ['style', 1];
        yield 'inbox' => ['inbox', ['message1', 'message2', 'message3']];
        yield 'pushType' => ['pushType', 'notification'];
        yield 'extraData' => ['extraData', ['custom' => 'data']];
    }

    public function testToArrayReturnsFilteredArray(): void
    {
        $notification = $this->createEntity();
        $notification->setAlert('Test message');
        $notification->setTitle('Test Title');
        $notification->setBadgeAddNum(1);
        $notification->setTestMessage(true);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'alert' => 'Test message',
            'title' => 'Test Title',
            'badge_add_num' => 1,
            'test_message' => true,
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

    public function testBooleanPropertiesHandledCorrectly(): void
    {
        $notification = $this->createEntity();
        $notification->setTestMessage(true);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertTrue($result['test_message']);
    }

    public function testArrayPropertiesHandledCorrectly(): void
    {
        $notification = $this->createEntity();
        $notification->setExtras(['key1' => 'value1']);
        $notification->setInbox(['key1' => 'message1', 'key2' => 'message2']);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals(['key1' => 'value1'], $result['extras']);
        $this->assertEquals(['key1' => 'message1', 'key2' => 'message2'], $result['inbox']);
    }

    public function testIntentPropertyHandledCorrectly(): void
    {
        $notification = $this->createEntity();
        $notification->setIntent(['action' => 'view', 'data' => 'example://app']);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals(['action' => 'view', 'data' => 'example://app'], $result['intent']);
    }
}
