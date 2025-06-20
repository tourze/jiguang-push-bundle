<?php

namespace JiguangPushBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Device;
use JiguangPushBundle\Entity\Tag;
use PHPUnit\Framework\TestCase;

class DeviceTest extends TestCase
{
    private Device $device;
    private Account $account;

    protected function setUp(): void
    {
        $this->device = new Device();
        $this->account = new Account();
        // 不能设置ID，它是自动生成的
        $this->account->setTitle('测试账号');
        $this->account->setAppKey('test_app_key');
        $this->account->setMasterSecret('test_master_secret');
    }

    public function testGetId(): void
    {
        // 在未设置ID的情况下，应该返回初始值
        $this->assertNotNull($this->device->getId());
    }

    public function testGetSetAccount(): void
    {
        $this->device->setAccount($this->account);
        $this->assertSame($this->account, $this->device->getAccount());
    }

    public function testGetSetRegistrationId(): void
    {
        $registrationId = 'test-registration-id-12345';
        $this->device->setRegistrationId($registrationId);
        $this->assertSame($registrationId, $this->device->getRegistrationId());
    }

    public function testGetSetAlias(): void
    {
        $alias = 'test-alias';
        $this->device->setAlias($alias);
        $this->assertSame($alias, $this->device->getAlias());
    }

    public function testGetSetMobile(): void
    {
        $mobile = '13800138000';
        $this->device->setMobile($mobile);
        $this->assertSame($mobile, $this->device->getMobile());
    }

    public function testGetSetCreateTime(): void
    {
        $date = new \DateTimeImmutable();
        $this->device->setCreateTime($date);
        $this->assertSame($date, $this->device->getCreateTime());
    }

    public function testGetSetUpdateTime(): void
    {
        $date = new \DateTimeImmutable();
        $this->device->setUpdateTime($date);
        $this->assertSame($date, $this->device->getUpdateTime());
    }

    public function testTagsCollectionInitialization(): void
    {
        $tags = $this->device->getTags();
        $this->assertInstanceOf(ArrayCollection::class, $tags);
        $this->assertCount(0, $tags);
    }

    public function testAddRemoveTag(): void
    {
        $tag = new Tag();
        // 不能设置Tag的ID，它是自动生成的
        $tag->setValue('test-tag');

        // 添加标签
        $result = $this->device->addTag($tag);
        $this->assertSame($this->device, $result);
        $this->assertCount(1, $this->device->getTags());
        $this->assertTrue($this->device->getTags()->contains($tag));

        // 再次添加相同标签不会重复
        $this->device->addTag($tag);
        $this->assertCount(1, $this->device->getTags());

        // 移除标签
        $result = $this->device->removeTag($tag);
        $this->assertSame($this->device, $result);
        $this->assertCount(0, $this->device->getTags());
        $this->assertFalse($this->device->getTags()->contains($tag));
    }

    public function testToArray(): void
    {
        // 检查是否存在toArray方法
        if (method_exists($this->device, 'toArray')) {
            $registrationId = 'test-registration-id';
            $alias = 'test-alias';
            $mobile = '13800138000';
            $createTime = new \DateTimeImmutable('2022-01-01 12:00:00');
            $updateTime = new \DateTimeImmutable('2022-01-02 12:00:00');

            $this->device->setAccount($this->account);
            $this->device->setRegistrationId($registrationId);
            $this->device->setAlias($alias);
            $this->device->setMobile($mobile);
            $this->device->setCreateTime($createTime);
            $this->device->setUpdateTime($updateTime);

            // 添加标签
            $tag = new Tag();
            $tag->setValue('test-tag');
            $this->device->addTag($tag);

            $data = $this->device->toArray();
            $this->assertArrayHasKey('id', $data);

            if (isset($data['account'])) {
                $this->assertArrayHasKey('id', $data['account']);
            }

            $this->assertArrayHasKey('registrationId', $data);
            $this->assertSame($registrationId, $data['registrationId']);

            $this->assertArrayHasKey('alias', $data);
            $this->assertSame($alias, $data['alias']);

            $this->assertArrayHasKey('mobile', $data);
            $this->assertSame($mobile, $data['mobile']);

            if (isset($data['tags'])) {
                $this->assertNotEmpty($data['tags']);
            }
        } else {
            $this->markTestSkipped('toArray method is not implemented in Device class');
        }
    }
}
