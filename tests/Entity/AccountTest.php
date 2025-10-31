<?php

namespace JiguangPushBundle\Tests\Entity;

use JiguangPushBundle\Entity\Account;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(Account::class)]
final class AccountTest extends AbstractEntityTestCase
{
    protected function createEntity(): Account
    {
        return new Account();
    }

    /** @return iterable<string, array{string, mixed}> */
    public static function propertiesProvider(): iterable
    {
        yield 'title' => ['title', '测试账号'];
        yield 'appKey' => ['appKey', 'test_app_key_12345'];
        yield 'masterSecret' => ['masterSecret', 'test_master_secret_12345'];
        yield 'valid' => ['valid', true];
        yield 'createdBy' => ['createdBy', 'admin'];
        yield 'updatedBy' => ['updatedBy', 'admin'];
        yield 'createTime' => ['createTime', new \DateTimeImmutable()];
        yield 'updateTime' => ['updateTime', new \DateTimeImmutable()];
    }

    public function testGetId(): void
    {
        // id 是自动生成的，初始值应该是0
        $entity = $this->createEntity();
        $this->assertSame(0, $entity->getId());
    }
}
