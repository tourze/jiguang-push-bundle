<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\AndroidNotificationExtras;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(AndroidNotificationExtras::class)]
final class AndroidNotificationExtrasTest extends TestCase
{
    protected function createEntity(): AndroidNotificationExtras
    {
        return new AndroidNotificationExtras();
    }

    #[DataProvider('propertiesProvider')]
    public function testGettersAndSetters(string $property, string $value): void
    {
        $entity = $this->createEntity();
        $setter = 'set' . ucfirst($property);
        $getter = 'get' . ucfirst($property);

        $this->assertTrue(method_exists($entity, $setter), "Setter {$setter} does not exist");
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
        yield 'mipnsContentForshort' => ['mipnsContentForshort', '小米通道通知消息的长描述'];
        yield 'oppnsContentForshort' => ['oppnsContentForshort', 'OPPO通道通知消息的长描述'];
        yield 'vpnsContentForshort' => ['vpnsContentForshort', 'vivo通道通知消息的长描述'];
        yield 'mzpnsContentForshort' => ['mzpnsContentForshort', '魅族通道通知消息的长描述'];
    }

    public function testToArrayReturnsFilteredArray(): void
    {
        $extras = $this->createEntity();
        $extras->setMipnsContentForshort('小米长描述');
        $extras->setOppnsContentForshort('OPPO长描述');

        $result = $extras->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'mipns_content_forshort' => '小米长描述',
            'oppns_content_forshort' => 'OPPO长描述',
        ], $result);
    }

    public function testToArrayExcludesNullValues(): void
    {
        $extras = $this->createEntity();
        $extras->setMipnsContentForshort('小米长描述');
        $extras->setOppnsContentForshort(null);

        $result = $extras->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'mipns_content_forshort' => '小米长描述',
        ], $result);
        $this->assertArrayNotHasKey('oppns_content_forshort', $result);
    }

    public function testToArrayReturnsEmptyArrayWhenAllNull(): void
    {
        $extras = $this->createEntity();

        $result = $extras->toArray();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testPropertyMaxLengths(): void
    {
        $extras = $this->createEntity();

        // Test maximum length for each property
        $extras->setMipnsContentForshort(str_repeat('a', 1000));
        $extras->setOppnsContentForshort(str_repeat('b', 200));
        $extras->setVpnsContentForshort(str_repeat('c', 100));
        $extras->setMzpnsContentForshort(str_repeat('d', 128));

        $result = $extras->toArray();

        $this->assertIsArray($result);
        $this->assertCount(4, $result);
        $this->assertIsString($result['mipns_content_forshort']);
        $this->assertEquals(1000, strlen($result['mipns_content_forshort']));
        $this->assertIsString($result['oppns_content_forshort']);
        $this->assertEquals(200, strlen($result['oppns_content_forshort']));
        $this->assertIsString($result['vpns_content_forshort']);
        $this->assertEquals(100, strlen($result['vpns_content_forshort']));
        $this->assertIsString($result['mzpns_content_forshort']);
        $this->assertEquals(128, strlen($result['mzpns_content_forshort']));
    }
}
