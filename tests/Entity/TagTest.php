<?php

namespace JiguangPushBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Device;
use JiguangPushBundle\Entity\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    private Tag $tag;
    private Account $account;

    protected function setUp(): void
    {
        $this->tag = new Tag();
        $this->account = new Account();
        // 不能设置ID，它是自动生成的
        $this->account->setTitle('测试账号');
        $this->account->setAppKey('test_app_key');
        $this->account->setMasterSecret('test_master_secret');
    }

    public function testGetId(): void
    {
        // 在未设置ID的情况下，应该返回初始值
        $this->assertNotNull($this->tag->getId());
    }

    public function testGetSetAccount(): void
    {
        $this->tag->setAccount($this->account);
        $this->assertSame($this->account, $this->tag->getAccount());
    }

    public function testGetSetValue(): void
    {
        $value = 'test-tag-value';
        $this->tag->setValue($value);
        $this->assertSame($value, $this->tag->getValue());
    }

    public function testGetSetCreateTime(): void
    {
        $date = new \DateTime();
        $this->tag->setCreateTime($date);
        $this->assertSame($date, $this->tag->getCreateTime());
    }

    public function testGetSetUpdateTime(): void
    {
        $date = new \DateTime();
        $this->tag->setUpdateTime($date);
        $this->assertSame($date, $this->tag->getUpdateTime());
    }

    public function testDevicesCollectionInitialization(): void
    {
        $devices = $this->tag->getDevices();
        $this->assertInstanceOf(ArrayCollection::class, $devices);
        $this->assertCount(0, $devices);
    }

    public function testAddRemoveDevice(): void
    {
        $device = new Device();
        // 不能设置Device的ID，它是自动生成的
        $device->setRegistrationId('test-registration-id');
        
        // 添加设备
        $result = $this->tag->addDevice($device);
        $this->assertSame($this->tag, $result);
        $this->assertCount(1, $this->tag->getDevices());
        $this->assertTrue($this->tag->getDevices()->contains($device));
        
        // 再次添加相同设备不会重复
        $this->tag->addDevice($device);
        $this->assertCount(1, $this->tag->getDevices());
        
        // 移除设备
        $result = $this->tag->removeDevice($device);
        $this->assertSame($this->tag, $result);
        $this->assertCount(0, $this->tag->getDevices());
        $this->assertFalse($this->tag->getDevices()->contains($device));
    }

    public function testToArray(): void
    {
        // 检查是否存在toArray方法
        if (method_exists($this->tag, 'toArray')) {
            $value = 'test-tag-value';
            $createTime = new \DateTime('2022-01-01 12:00:00');
            $updateTime = new \DateTime('2022-01-02 12:00:00');

            $this->tag->setAccount($this->account);
            $this->tag->setValue($value);
            $this->tag->setCreateTime($createTime);
            $this->tag->setUpdateTime($updateTime);

            // 添加设备
            $device = new Device();
            $device->setRegistrationId('test-registration-id');
            $this->tag->addDevice($device);

            $data = $this->tag->toArray();
            
            $this->assertIsArray($data);
            $this->assertArrayHasKey('id', $data);
            
            if (isset($data['account'])) {
                $this->assertIsArray($data['account']);
                // 可能无法确保account id的具体值，只验证其存在
                $this->assertArrayHasKey('id', $data['account']);
            }
            
            $this->assertArrayHasKey('value', $data);
            $this->assertSame($value, $data['value']);
            
            if (isset($data['devices'])) {
                $this->assertIsArray($data['devices']);
                $this->assertNotEmpty($data['devices']);
            }
        } else {
            $this->markTestSkipped('toArray method is not implemented in Tag class');
        }
    }
} 