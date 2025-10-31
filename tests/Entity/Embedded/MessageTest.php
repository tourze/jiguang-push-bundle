<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\Message;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Message::class)]
final class MessageTest extends TestCase
{
    protected function createEntity(): Message
    {
        return new Message();
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
        yield 'msgContent' => ['msgContent', 'Test message content'];
        yield 'contentType' => ['contentType', 'text/plain'];
        yield 'title' => ['title', 'Test Message Title'];
        yield 'extras' => ['extras', ['key1' => 'value1', 'key2' => 'value2']];
    }

    public function testToArrayReturnsFilteredArray(): void
    {
        $message = $this->createEntity();
        $message->setMsgContent('Test message content');
        $message->setTitle('Test Title');
        $message->setExtras(['custom' => 'data']);

        $result = $message->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'msg_content' => 'Test message content',
            'title' => 'Test Title',
            'extras' => ['custom' => 'data'],
        ], $result);
    }

    public function testToArrayExcludesNullValues(): void
    {
        $message = $this->createEntity();
        $message->setMsgContent('Test message content');
        $message->setContentType(null);

        $result = $message->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'msg_content' => 'Test message content',
        ], $result);
        $this->assertArrayNotHasKey('content_type', $result);
    }

    public function testToArrayReturnsEmptyArrayWhenAllNull(): void
    {
        $message = $this->createEntity();

        $result = $message->toArray();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testArrayPropertiesHandledCorrectly(): void
    {
        $message = $this->createEntity();
        $extras = ['key1' => 'value1', 'key2' => 'value2', 'nested' => ['data' => 'test']];
        $message->setExtras($extras);

        $result = $message->toArray();

        $this->assertIsArray($result);
        $this->assertEquals($extras, $result['extras']);
    }

    public function testSettersWork(): void
    {
        $message = $this->createEntity();

        $message->setMsgContent('Test content');
        $message->setContentType('text/html');
        $message->setTitle('Test Title');
        $message->setExtras(['key' => 'value']);

        $this->assertEquals('Test content', $message->getMsgContent());
        $this->assertEquals('text/html', $message->getContentType());
        $this->assertEquals('Test Title', $message->getTitle());
        $this->assertEquals(['key' => 'value'], $message->getExtras());
    }

    public function testNullValueHandling(): void
    {
        $message = $this->createEntity();

        $this->assertNull($message->getMsgContent());
        $this->assertNull($message->getContentType());
        $this->assertNull($message->getTitle());
        $this->assertNull($message->getExtras());
    }
}
