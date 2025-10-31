<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\Audience;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Audience::class)]
final class AudienceTest extends TestCase
{
    protected function createEntity(): Audience
    {
        return new Audience();
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
        yield 'all' => ['all', true];
        yield 'tag' => ['tag', ['tag1', 'tag2']];
        yield 'tagAnd' => ['tagAnd', ['tag1', 'tag2']];
        yield 'tagNot' => ['tagNot', ['tag3']];
        yield 'alias' => ['alias', ['user1', 'user2']];
        yield 'registrationId' => ['registrationId', ['reg1', 'reg2']];
        yield 'segment' => ['segment', ['segment1']];
        yield 'abTest' => ['abTest', ['test1']];
    }

    public function testToDataReturnsAllWhenAllIsTrue(): void
    {
        $audience = $this->createEntity();
        $audience->setAll(true);

        $this->assertSame('all', $audience->toData());
    }

    public function testToDataReturnsFilteredArrayWhenAllIsFalse(): void
    {
        $audience = $this->createEntity();
        $audience->setAll(false);
        $audience->setTag(['tag1', 'tag2']);
        $audience->setAlias(['user1']);

        $result = $audience->toData();

        $this->assertIsArray($result);
        $this->assertEquals(['tag' => ['tag1', 'tag2'], 'alias' => ['user1']], $result);
    }

    public function testToDataReturnsEmptyArrayWhenNoData(): void
    {
        $audience = $this->createEntity();
        $audience->setAll(false);

        $result = $audience->toData();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testToArrayReturnsArrayWithAllKeyWhenToDataReturnsString(): void
    {
        $audience = $this->createEntity();
        $audience->setAll(true);

        $result = $audience->toArray();

        $this->assertIsArray($result);
        $this->assertEquals(['all' => true], $result);
    }

    public function testToArrayReturnsSameArrayWhenToDataReturnsArray(): void
    {
        $audience = $this->createEntity();
        $audience->setAll(false);
        $audience->setTag(['tag1']);

        $result = $audience->toArray();

        $this->assertIsArray($result);
        $this->assertEquals(['tag' => ['tag1']], $result);
    }
}
