<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\LiveActivity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(LiveActivity::class)]
final class LiveActivityTest extends TestCase
{
    protected function createEntity(): LiveActivity
    {
        return new LiveActivity();
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
        yield 'activityId' => ['activityId', 'activity-123'];
        yield 'pushToken' => ['pushToken', 'push-token-456'];
        yield 'event' => ['event', ['type' => 'start', 'data' => 'test']];
        yield 'timestamp' => ['timestamp', 1640995200];
        yield 'contentState' => ['contentState', ['title' => 'Live Activity', 'status' => 'active']];
        yield 'staleDate' => ['staleDate', ['date' => '2024-01-01', 'time' => '12:00:00']];
        yield 'relevanceScore' => ['relevanceScore', 10];
    }

    public function testActivityIdMaxLength(): void
    {
        $activity = $this->createEntity();
        $longId = str_repeat('a', 100);
        $activity->setActivityId($longId);

        $this->assertEquals($longId, $activity->getActivityId());
    }

    public function testPushTokenMaxLength(): void
    {
        $activity = $this->createEntity();
        $longToken = str_repeat('b', 100);
        $activity->setPushToken($longToken);

        $this->assertEquals($longToken, $activity->getPushToken());
    }

    public function testArrayProperties(): void
    {
        $activity = $this->createEntity();

        $event = ['type' => 'update', 'data' => 'new content'];
        $contentState = ['title' => 'Updated Activity', 'status' => 'paused'];
        $staleDate = ['date' => '2024-12-31', 'time' => '23:59:59'];

        $activity->setEvent($event);
        $activity->setContentState($contentState);
        $activity->setStaleDate($staleDate);

        $this->assertEquals($event, $activity->getEvent());
        $this->assertEquals($contentState, $activity->getContentState());
        $this->assertEquals($staleDate, $activity->getStaleDate());
    }

    public function testTimestampProperty(): void
    {
        $activity = $this->createEntity();
        $timestamp = time();

        $activity->setTimestamp($timestamp);

        $this->assertEquals($timestamp, $activity->getTimestamp());
    }

    public function testRelevanceScoreProperty(): void
    {
        $activity = $this->createEntity();
        $score = 15;

        $activity->setRelevanceScore($score);

        $this->assertEquals($score, $activity->getRelevanceScore());
    }

    public function testNullValueHandling(): void
    {
        $activity = $this->createEntity();

        $this->assertNull($activity->getActivityId());
        $this->assertNull($activity->getPushToken());
        $this->assertNull($activity->getEvent());
        $this->assertNull($activity->getTimestamp());
        $this->assertNull($activity->getContentState());
        $this->assertNull($activity->getStaleDate());
        $this->assertNull($activity->getRelevanceScore());
    }

    public function testSettersWork(): void
    {
        $activity = $this->createEntity();

        $activity->setActivityId('test-activity');
        $activity->setPushToken('test-token');
        $activity->setTimestamp(1640995200);
        $activity->setRelevanceScore(5);

        $this->assertEquals('test-activity', $activity->getActivityId());
        $this->assertEquals('test-token', $activity->getPushToken());
        $this->assertEquals(1640995200, $activity->getTimestamp());
        $this->assertEquals(5, $activity->getRelevanceScore());
    }
}
