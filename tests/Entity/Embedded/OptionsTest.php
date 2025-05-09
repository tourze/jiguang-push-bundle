<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    private Options $options;

    protected function setUp(): void
    {
        $this->options = new Options();
    }

    public function testGetSetTimeToLive(): void
    {
        $ttl = 86400;
        $this->options->setTimeToLive($ttl);
        $this->assertSame($ttl, $this->options->getTimeToLive());
        
        $this->options->setTimeToLive(null);
        $this->assertNull($this->options->getTimeToLive());
    }

    public function testGetSetApnsProduction(): void
    {
        $this->options->setApnsProduction(true);
        $this->assertTrue($this->options->isApnsProduction());
        
        $this->options->setApnsProduction(false);
        $this->assertFalse($this->options->isApnsProduction());
        
        $this->options->setApnsProduction(null);
        $this->assertNull($this->options->isApnsProduction());
    }

    public function testGetSetApnsCollapseId(): void
    {
        $collapseId = 'test-collapse-id';
        $this->options->setApnsCollapseId($collapseId);
        $this->assertSame($collapseId, $this->options->getApnsCollapseId());
        
        $this->options->setApnsCollapseId(null);
        $this->assertNull($this->options->getApnsCollapseId());
    }

    public function testGetSetBigPushDuration(): void
    {
        $duration = 3600;
        $this->options->setBigPushDuration($duration);
        $this->assertSame($duration, $this->options->getBigPushDuration());
        
        $this->options->setBigPushDuration(null);
        $this->assertNull($this->options->getBigPushDuration());
    }

    public function testGetSetThirdPartyChannel(): void
    {
        $channel = [
            'huawei' => [
                'channel_id' => 'test_huawei_channel',
            ],
            'xiaomi' => [
                'channel_id' => 'test_xiaomi_channel',
            ],
        ];
        $this->options->setThirdPartyChannel($channel);
        $this->assertSame($channel, $this->options->getThirdPartyChannel());
        
        $this->options->setThirdPartyChannel(null);
        $this->assertNull($this->options->getThirdPartyChannel());
    }

    public function testGetSetOverride(): void
    {
        $this->options->setOverride(true);
        $this->assertTrue($this->options->isOverride());
        
        $this->options->setOverride(false);
        $this->assertFalse($this->options->isOverride());
        
        $this->options->setOverride(null);
        $this->assertNull($this->options->isOverride());
    }

    public function testGetSetUniqueKey(): void
    {
        $uniqueKey = 'test-unique-key';
        $this->options->setUniqueKey($uniqueKey);
        $this->assertSame($uniqueKey, $this->options->getUniqueKey());
        
        $this->options->setUniqueKey(null);
        $this->assertNull($this->options->getUniqueKey());
    }

    public function testGetSetWithdrawable(): void
    {
        $this->options->setWithdrawable(true);
        $this->assertTrue($this->options->isWithdrawable());
        
        $this->options->setWithdrawable(false);
        $this->assertFalse($this->options->isWithdrawable());
        
        $this->options->setWithdrawable(null);
        $this->assertNull($this->options->isWithdrawable());
    }

    public function testGetSetThirdPartyEnable(): void
    {
        $this->options->setThirdPartyEnable(true);
        $this->assertTrue($this->options->isThirdPartyEnable());
        
        $this->options->setThirdPartyEnable(false);
        $this->assertFalse($this->options->isThirdPartyEnable());
        
        $this->options->setThirdPartyEnable(null);
        $this->assertNull($this->options->isThirdPartyEnable());
    }

    public function testGetSetCacheable(): void
    {
        $this->options->setCacheable(true);
        $this->assertTrue($this->options->isCacheable());
        
        $this->options->setCacheable(false);
        $this->assertFalse($this->options->isCacheable());
        
        $this->options->setCacheable(null);
        $this->assertNull($this->options->isCacheable());
    }

    public function testGetSetSync(): void
    {
        $this->options->setSync(true);
        $this->assertTrue($this->options->isSync());
        
        $this->options->setSync(false);
        $this->assertFalse($this->options->isSync());
        
        $this->options->setSync(null);
        $this->assertNull($this->options->isSync());
    }

    public function testToArrayWithMinimalFields(): void
    {
        // 没有设置任何字段
        $data = $this->options->toArray();
        
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }

    public function testToArrayWithAllFields(): void
    {
        $ttl = 86400;
        $apnsProduction = true;
        $collapseId = 'test-collapse-id';
        $channel = ['huawei' => ['channel_id' => 'test_huawei_channel']];
        $override = true;
        $uniqueKey = 'test-unique-key';
        $duration = 3600;
        $withdrawable = true;
        $thirdPartyEnable = true;
        $cacheable = true;
        $sync = true;
        
        $this->options->setTimeToLive($ttl);
        $this->options->setApnsProduction($apnsProduction);
        $this->options->setApnsCollapseId($collapseId);
        $this->options->setThirdPartyChannel($channel);
        $this->options->setOverride($override);
        $this->options->setUniqueKey($uniqueKey);
        $this->options->setBigPushDuration($duration);
        $this->options->setWithdrawable($withdrawable);
        $this->options->setThirdPartyEnable($thirdPartyEnable);
        $this->options->setCacheable($cacheable);
        $this->options->setSync($sync);
        
        $data = $this->options->toArray();
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('time_to_live', $data);
        $this->assertArrayHasKey('apns_production', $data);
        $this->assertArrayHasKey('apns_collapse_id', $data);
        $this->assertArrayHasKey('third_party_channel', $data);
        $this->assertArrayHasKey('override', $data);
        $this->assertArrayHasKey('unique_key', $data);
        $this->assertArrayHasKey('big_push_duration', $data);
        $this->assertArrayHasKey('withdrawable', $data);
        $this->assertArrayHasKey('third_party_enable', $data);
        $this->assertArrayHasKey('cacheable', $data);
        $this->assertArrayHasKey('sync', $data);
        
        $this->assertSame($ttl, $data['time_to_live']);
        $this->assertSame($apnsProduction, $data['apns_production']);
        $this->assertSame($collapseId, $data['apns_collapse_id']);
        $this->assertSame($channel, $data['third_party_channel']);
        $this->assertSame($override, $data['override']);
        $this->assertSame($uniqueKey, $data['unique_key']);
        $this->assertSame($duration, $data['big_push_duration']);
        $this->assertSame($withdrawable, $data['withdrawable']);
        $this->assertSame($thirdPartyEnable, $data['third_party_enable']);
        $this->assertSame($cacheable, $data['cacheable']);
        $this->assertSame($sync, $data['sync']);
    }

    public function testToArrayOmitsNullFields(): void
    {
        // 只设置部分字段
        $ttl = 86400;
        $apnsProduction = true;
        
        $this->options->setTimeToLive($ttl);
        $this->options->setApnsProduction($apnsProduction);
        // 其他字段保持为null
        
        $data = $this->options->toArray();
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('time_to_live', $data);
        $this->assertArrayHasKey('apns_production', $data);
        $this->assertArrayNotHasKey('apns_collapse_id', $data);
        $this->assertArrayNotHasKey('third_party_channel', $data);
        $this->assertArrayNotHasKey('override', $data);
        $this->assertArrayNotHasKey('unique_key', $data);
        $this->assertArrayNotHasKey('big_push_duration', $data);
        $this->assertArrayNotHasKey('withdrawable', $data);
        $this->assertArrayNotHasKey('third_party_enable', $data);
        $this->assertArrayNotHasKey('cacheable', $data);
        $this->assertArrayNotHasKey('sync', $data);
        
        $this->assertSame($ttl, $data['time_to_live']);
        $this->assertSame($apnsProduction, $data['apns_production']);
    }
}