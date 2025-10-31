<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\IosNotification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(IosNotification::class)]
final class IosNotificationTest extends TestCase
{
    protected function createEntity(): IosNotification
    {
        return new IosNotification();
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
        yield 'sound' => ['sound', 'default'];
        yield 'badge' => ['badge', 5];
        yield 'contentAvailable' => ['contentAvailable', true];
        yield 'mutableContent' => ['mutableContent', false];
        yield 'category' => ['category', 'MESSAGE_CATEGORY'];
        yield 'extras' => ['extras', ['key1' => 'value1', 'key2' => 'value2']];
        yield 'threadId' => ['threadId', 'thread-123'];
        yield 'interruptionLevel' => ['interruptionLevel', 'active'];
    }

    public function testToArrayReturnsFilteredArray(): void
    {
        $notification = $this->createEntity();
        $notification->setAlert('Test message');
        $notification->setSound('default');
        $notification->setBadge(3);
        $notification->setContentAvailable(true);
        $notification->setExtras(['custom' => 'data']);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'alert' => 'Test message',
            'sound' => 'default',
            'badge' => 3,
            'content-available' => true,
            'extras' => ['custom' => 'data'],
        ], $result);
    }

    public function testToArrayExcludesNullValues(): void
    {
        $notification = $this->createEntity();
        $notification->setAlert('Test message');
        $notification->setSound(null);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'alert' => 'Test message',
        ], $result);
        $this->assertArrayNotHasKey('sound', $result);
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
        $notification->setContentAvailable(true);
        $notification->setMutableContent(true);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertTrue($result['content-available']);
        $this->assertTrue($result['mutable-content']);
    }

    public function testBooleanPropertiesAreNullByDefault(): void
    {
        $notification = $this->createEntity();

        $this->assertNull($notification->isContentAvailable());
        $this->assertNull($notification->isMutableContent());

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertArrayNotHasKey('content-available', $result);
        $this->assertArrayNotHasKey('mutable-content', $result);
    }
}
