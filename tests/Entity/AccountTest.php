<?php

namespace JiguangPushBundle\Tests\Entity;

use JiguangPushBundle\Entity\Account;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    private Account $account;

    protected function setUp(): void
    {
        $this->account = new Account();
    }

    public function testGetId(): void
    {
        // id 是自动生成的，初始值应该是null或0
        $this->assertIsInt($this->account->getId());
    }

    public function testGetSetTitle(): void
    {
        $this->account->setTitle('测试账号');
        $this->assertSame('测试账号', $this->account->getTitle());
    }

    public function testGetSetAppKey(): void
    {
        $appKey = 'test_app_key_12345';
        $this->account->setAppKey($appKey);
        $this->assertSame($appKey, $this->account->getAppKey());
    }

    public function testGetSetMasterSecret(): void
    {
        $secret = 'test_master_secret_12345';
        $this->account->setMasterSecret($secret);
        $this->assertSame($secret, $this->account->getMasterSecret());
    }

    public function testGetSetValid(): void
    {
        $this->account->setValid(true);
        $this->assertTrue($this->account->isValid());

        $this->account->setValid(false);
        $this->assertFalse($this->account->isValid());
    }

    public function testGetSetCreatedBy(): void
    {
        $createdBy = 'admin';
        $this->account->setCreatedBy($createdBy);
        $this->assertSame($createdBy, $this->account->getCreatedBy());
    }

    public function testGetSetUpdatedBy(): void
    {
        $updatedBy = 'admin';
        $this->account->setUpdatedBy($updatedBy);
        $this->assertSame($updatedBy, $this->account->getUpdatedBy());
    }

    public function testGetSetCreateTime(): void
    {
        $date = new \DateTimeImmutable();
        $this->account->setCreateTime($date);
        $this->assertSame($date, $this->account->getCreateTime());
    }

    public function testGetSetUpdateTime(): void
    {
        $date = new \DateTimeImmutable();
        $this->account->setUpdateTime($date);
        $this->assertSame($date, $this->account->getUpdateTime());
    }

}
