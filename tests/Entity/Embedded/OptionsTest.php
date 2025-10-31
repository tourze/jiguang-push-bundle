<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\Options;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Options::class)]
final class OptionsTest extends TestCase
{
    protected function createEntity(): Options
    {
        return new Options();
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
        yield 'timeToLive' => ['timeToLive', 86400];
        yield 'apnsProduction' => ['apnsProduction', true];
        yield 'apnsCollapseId' => ['apnsCollapseId', 'collapse-id-123'];
        yield 'thirdPartyChannel' => ['thirdPartyChannel', ['xiaomi' => ['app_id' => 'test']]];
        yield 'override' => ['override', false];
        yield 'uniqueKey' => ['uniqueKey', 'unique-key-456'];
        yield 'bigPushDuration' => ['bigPushDuration', 3600];
        yield 'withdrawable' => ['withdrawable', true];
        yield 'thirdPartyEnable' => ['thirdPartyEnable', false];
        yield 'cacheable' => ['cacheable', true];
        yield 'sync' => ['sync', false];
    }

    public function testToArrayReturnsFilteredArray(): void
    {
        $options = $this->createEntity();
        $options->setTimeToLive(86400);
        $options->setApnsProduction(true);
        $options->setUniqueKey('test-key');

        $result = $options->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'time_to_live' => 86400,
            'apns_production' => true,
            'unique_key' => 'test-key',
        ], $result);
    }

    public function testToArrayExcludesNullValues(): void
    {
        $options = $this->createEntity();
        $options->setTimeToLive(86400);
        $options->setApnsProduction(null);

        $result = $options->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'time_to_live' => 86400,
        ], $result);
        $this->assertArrayNotHasKey('apns_production', $result);
    }

    public function testToArrayReturnsEmptyArrayWhenAllNull(): void
    {
        $options = $this->createEntity();

        $result = $options->toArray();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testBooleanPropertiesHandledCorrectly(): void
    {
        $options = $this->createEntity();
        $options->setApnsProduction(true);
        $options->setOverride(true);
        $options->setWithdrawable(true);

        $result = $options->toArray();

        $this->assertIsArray($result);
        $this->assertTrue($result['apns_production']);
        $this->assertTrue($result['override']);
        $this->assertTrue($result['withdrawable']);
    }

    public function testThirdPartyChannelProperty(): void
    {
        $options = $this->createEntity();
        $channelConfig = [
            'xiaomi' => ['app_id' => 'xiaomi-app-id', 'app_key' => 'xiaomi-app-key'],
            'huawei' => ['app_id' => 'huawei-app-id'],
        ];
        $options->setThirdPartyChannel($channelConfig);

        $result = $options->toArray();

        $this->assertIsArray($result);
        $this->assertEquals($channelConfig, $result['third_party_channel']);
    }

    public function testIntegerProperties(): void
    {
        $options = $this->createEntity();
        $options->setTimeToLive(86400);
        $options->setBigPushDuration(3600);

        $result = $options->toArray();

        $this->assertIsArray($result);
        $this->assertEquals(86400, $result['time_to_live']);
        $this->assertEquals(3600, $result['big_push_duration']);
    }

    public function testSettersWork(): void
    {
        $options = $this->createEntity();

        $options->setTimeToLive(86400);
        $options->setApnsProduction(true);
        $options->setUniqueKey('test-key');
        $options->setWithdrawable(true);

        $this->assertEquals(86400, $options->getTimeToLive());
        $this->assertTrue($options->isApnsProduction());
        $this->assertEquals('test-key', $options->getUniqueKey());
        $this->assertTrue($options->isWithdrawable());
    }

    public function testNullValueHandling(): void
    {
        $options = $this->createEntity();

        $this->assertNull($options->getTimeToLive());
        $this->assertNull($options->isApnsProduction());
        $this->assertNull($options->getApnsCollapseId());
        $this->assertNull($options->getThirdPartyChannel());
        $this->assertNull($options->isOverride());
        $this->assertNull($options->getUniqueKey());
        $this->assertNull($options->getBigPushDuration());
        $this->assertNull($options->isWithdrawable());
        $this->assertNull($options->isThirdPartyEnable());
        $this->assertNull($options->isCacheable());
        $this->assertNull($options->isSync());
    }
}
