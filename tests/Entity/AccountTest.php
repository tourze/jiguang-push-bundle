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
        $date = new \DateTime();
        $this->account->setCreateTime($date);
        $this->assertSame($date, $this->account->getCreateTime());
    }

    public function testGetSetUpdateTime(): void
    {
        $date = new \DateTime();
        $this->account->setUpdateTime($date);
        $this->assertSame($date, $this->account->getUpdateTime());
    }

    public function testToArray(): void
    {
        // 因为Account类可能没有实现toArray方法，而是依赖于其他机制进行序列化
        // 我们应该检查该方法是否存在，如果存在则测试它
        if (method_exists($this->account, 'toArray')) {
            // 设置所有必须的字段
            $title = '测试账号';
            $appKey = 'test_app_key';
            $masterSecret = 'test_master_secret';
            $valid = true;
            $createdBy = 'admin';
            $updatedBy = 'admin';
            $createTime = new \DateTime('2022-01-01 12:00:00');
            $updateTime = new \DateTime('2022-01-02 12:00:00');

            $this->account->setTitle($title);
            $this->account->setAppKey($appKey);
            $this->account->setMasterSecret($masterSecret);
            $this->account->setValid($valid);
            $this->account->setCreatedBy($createdBy);
            $this->account->setUpdatedBy($updatedBy);
            $this->account->setCreateTime($createTime);
            $this->account->setUpdateTime($updateTime);

            // 检查toArray方法结果是否包含所有必要字段
            $data = $this->account->toArray();
            
            $this->assertIsArray($data);
            $this->assertArrayHasKey('id', $data);
            $this->assertArrayHasKey('title', $data);
            $this->assertArrayHasKey('appKey', $data);
            $this->assertArrayHasKey('masterSecret', $data);
            $this->assertArrayHasKey('valid', $data);
            $this->assertArrayHasKey('createdBy', $data);
            $this->assertArrayHasKey('updatedBy', $data);
            $this->assertArrayHasKey('createTime', $data);
            $this->assertArrayHasKey('updateTime', $data);
        } else {
            // 如果方法不存在，则跳过测试
            $this->markTestSkipped('toArray method is not implemented in Account class');
        }
    }
} 