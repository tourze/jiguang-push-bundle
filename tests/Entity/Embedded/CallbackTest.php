<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\Callback;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Callback::class)]
final class CallbackTest extends TestCase
{
    protected function createEntity(): Callback
    {
        return new Callback();
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
        yield 'url' => ['url', 'https://example.com/callback'];
        yield 'params' => ['params', ['param1' => 'value1', 'param2' => 'value2']];
        yield 'type' => ['type', 'webhook'];
    }

    public function testToArrayReturnsFilteredArray(): void
    {
        $callback = $this->createEntity();
        $callback->setUrl('https://example.com/callback');
        $callback->setParams(['param1' => 'value1']);
        $callback->setType('webhook');

        $result = $callback->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'url' => 'https://example.com/callback',
            'params' => ['param1' => 'value1'],
            'type' => 'webhook',
        ], $result);
    }

    public function testToArrayExcludesNullValues(): void
    {
        $callback = $this->createEntity();
        $callback->setUrl('https://example.com/callback');
        $callback->setType(null);

        $result = $callback->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'url' => 'https://example.com/callback',
        ], $result);
        $this->assertArrayNotHasKey('type', $result);
    }

    public function testToArrayReturnsEmptyArrayWhenAllNull(): void
    {
        $callback = $this->createEntity();

        $result = $callback->toArray();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
