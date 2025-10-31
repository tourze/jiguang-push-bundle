<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\VoipNotification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(VoipNotification::class)]
final class VoipNotificationTest extends TestCase
{
    protected function createEntity(): VoipNotification
    {
        return new VoipNotification();
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
        yield 'content' => ['content', ['title' => 'VoIP Call', 'body' => 'Incoming call']];
    }

    public function testToArrayReturnsContentArray(): void
    {
        $notification = $this->createEntity();
        $content = ['title' => 'VoIP Call', 'body' => 'Incoming call', 'data' => ['call_id' => '123']];
        $notification->setContent($content);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals($content, $result);
    }

    public function testToArrayReturnsEmptyArrayWhenContentIsNull(): void
    {
        $notification = $this->createEntity();
        $notification->setContent(null);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testToArrayReturnsEmptyArrayByDefault(): void
    {
        $notification = $this->createEntity();

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testContentPropertyHandling(): void
    {
        $notification = $this->createEntity();

        // Test with complex content structure
        $complexContent = [
            'title' => 'VoIP Call',
            'body' => 'Incoming call from John Doe',
            'sound' => 'ringtone.caf',
            'badge' => 1,
            'payload' => [
                'call_id' => '12345',
                'caller_id' => '67890',
                'call_type' => 'video',
            ],
        ];

        $notification->setContent($complexContent);

        $this->assertEquals($complexContent, $notification->getContent());

        $result = $notification->toArray();
        $this->assertEquals($complexContent, $result);
    }

    public function testSettersWork(): void
    {
        $notification = $this->createEntity();

        $notification->setContent(['title' => 'Test Call']);

        $this->assertEquals(['title' => 'Test Call'], $notification->getContent());
    }

    public function testNullValueHandling(): void
    {
        $notification = $this->createEntity();

        $this->assertNull($notification->getContent());

        // Test setting and getting null
        $notification->setContent(null);
        $this->assertNull($notification->getContent());

        // Test toArray returns empty array when content is null
        $result = $notification->toArray();
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testEmptyArrayHandling(): void
    {
        $notification = $this->createEntity();

        // Test with empty array
        $notification->setContent([]);

        $this->assertEquals([], $notification->getContent());

        $result = $notification->toArray();
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
