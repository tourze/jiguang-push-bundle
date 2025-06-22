<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\Audience;
use PHPUnit\Framework\TestCase;

class AudienceTest extends TestCase
{
    private Audience $audience;

    protected function setUp(): void
    {
        $this->audience = new Audience();
    }

    public function testGetSetAll(): void
    {
        $this->audience->setAll(true);
        $this->assertTrue($this->audience->isAll());
        
        $this->audience->setAll(false);
        $this->assertFalse($this->audience->isAll());
    }

    public function testGetSetTag(): void
    {
        $tags = ['tag1', 'tag2', 'tag3'];
        $this->audience->setTag($tags);
        $this->assertSame($tags, $this->audience->getTag());
        
        $this->audience->setTag(null);
        $this->assertNull($this->audience->getTag());
    }

    public function testGetSetTagAnd(): void
    {
        $tags = ['tag1', 'tag2', 'tag3'];
        $this->audience->setTagAnd($tags);
        $this->assertSame($tags, $this->audience->getTagAnd());
        
        $this->audience->setTagAnd(null);
        $this->assertNull($this->audience->getTagAnd());
    }

    public function testGetSetTagNot(): void
    {
        $tags = ['tag1', 'tag2', 'tag3'];
        $this->audience->setTagNot($tags);
        $this->assertSame($tags, $this->audience->getTagNot());
        
        $this->audience->setTagNot(null);
        $this->assertNull($this->audience->getTagNot());
    }

    public function testGetSetAlias(): void
    {
        $aliases = ['alias1', 'alias2', 'alias3'];
        $this->audience->setAlias($aliases);
        $this->assertSame($aliases, $this->audience->getAlias());
        
        $this->audience->setAlias(null);
        $this->assertNull($this->audience->getAlias());
    }

    public function testGetSetRegistrationId(): void
    {
        $ids = ['id1', 'id2', 'id3'];
        $this->audience->setRegistrationId($ids);
        $this->assertSame($ids, $this->audience->getRegistrationId());
        
        $this->audience->setRegistrationId(null);
        $this->assertNull($this->audience->getRegistrationId());
    }

    public function testGetSetSegment(): void
    {
        $segments = ['segment1', 'segment2'];
        $this->audience->setSegment($segments);
        $this->assertSame($segments, $this->audience->getSegment());
        
        $this->audience->setSegment(null);
        $this->assertNull($this->audience->getSegment());
    }

    public function testGetSetAbTest(): void
    {
        $tests = ['test1', 'test2'];
        $this->audience->setAbTest($tests);
        $this->assertSame($tests, $this->audience->getAbTest());
        
        $this->audience->setAbTest(null);
        $this->assertNull($this->audience->getAbTest());
    }

    public function testToDataWithAll(): void
    {
        $this->audience->setAll(true);
        
        $data = $this->audience->toData();
        $this->assertSame('all', $data);
    }

    public function testToDataWithTag(): void
    {
        $tags = ['tag1', 'tag2', 'tag3'];
        $this->audience->setTag($tags);
        
        $data = $this->audience->toData();
        $this->assertIsArray($data);
        $this->assertArrayHasKey('tag', $data);
        $this->assertSame($tags, $data['tag']);
    }

    public function testToDataWithMultipleTargets(): void
    {
        $aliases = ['alias1', 'alias2'];
        $this->audience->setAlias($aliases);
        
        $ids = ['id1', 'id2'];
        $this->audience->setRegistrationId($ids);
        
        $data = $this->audience->toData();
        $this->assertIsArray($data);
        $this->assertArrayHasKey('alias', $data);
        $this->assertArrayHasKey('registration_id', $data);
        $this->assertSame($aliases, $data['alias']);
        $this->assertSame($ids, $data['registration_id']);
    }

    public function testToDataPrioritizesAll(): void
    {
        $this->audience->setAll(true);
        $this->audience->setTag(['tag1', 'tag2']);
        $this->audience->setAlias(['alias1', 'alias2']);
        
        $data = $this->audience->toData();
        $this->assertSame('all', $data);
    }
    
    public function testToArrayWithAll(): void
    {
        $this->audience->setAll(true);
        
        $data = $this->audience->toArray();
        $this->assertArrayHasKey('all', $data);
        $this->assertTrue($data['all']);
    }
    
    public function testToArrayWithSpecificTargets(): void
    {
        $aliases = ['alias1', 'alias2'];
        $this->audience->setAlias($aliases);
        
        $data = $this->audience->toArray();
        $this->assertArrayHasKey('alias', $data);
        $this->assertSame($aliases, $data['alias']);
    }
} 