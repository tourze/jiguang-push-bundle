<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\QuickAppNotification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(QuickAppNotification::class)]
final class QuickAppNotificationTest extends TestCase
{
    protected function createEntity(): QuickAppNotification
    {
        return new QuickAppNotification();
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
        yield 'alert' => ['alert', 'Test quickapp notification'];
        yield 'title' => ['title', 'QuickApp Title'];
        yield 'page' => ['page', '/pages/detail/index'];
    }

    public function testToArrayReturnsFilteredArray(): void
    {
        $notification = $this->createEntity();
        $notification->setAlert('Test alert');
        $notification->setTitle('Test Title');
        $notification->setPage('/pages/home/index');

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'alert' => 'Test alert',
            'title' => 'Test Title',
            'page' => '/pages/home/index',
        ], $result);
    }

    public function testToArrayExcludesNullValues(): void
    {
        $notification = $this->createEntity();
        $notification->setAlert('Test alert');
        $notification->setTitle(null);

        $result = $notification->toArray();

        $this->assertIsArray($result);
        $this->assertEquals([
            'alert' => 'Test alert',
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

    public function testSettersWork(): void
    {
        $notification = $this->createEntity();

        $notification->setAlert('Test Alert');
        $notification->setTitle('Test Title');
        $notification->setPage('/pages/detail/index');

        $this->assertEquals('Test Alert', $notification->getAlert());
        $this->assertEquals('Test Title', $notification->getTitle());
        $this->assertEquals('/pages/detail/index', $notification->getPage());
    }

    public function testNullValueHandling(): void
    {
        $notification = $this->createEntity();

        $this->assertNull($notification->getAlert());
        $this->assertNull($notification->getTitle());
        $this->assertNull($notification->getPage());
    }

    public function testPagePropertyFormat(): void
    {
        $notification = $this->createEntity();

        // Test various page formats
        $pages = [
            '/pages/home/index',
            '/pages/detail/index?id=123',
            '/pages/user/profile',
            '/pages/about/us',
        ];

        foreach ($pages as $page) {
            $notification->setPage($page);
            $this->assertEquals($page, $notification->getPage());

            $result = $notification->toArray();
            $this->assertEquals($page, $result['page']);
        }
    }
}
