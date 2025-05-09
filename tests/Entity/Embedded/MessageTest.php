<?php

namespace JiguangPushBundle\Tests\Entity\Embedded;

use JiguangPushBundle\Entity\Embedded\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    private Message $message;

    protected function setUp(): void
    {
        $this->message = new Message();
    }

    public function testGetSetMsgContent(): void
    {
        $content = '测试消息内容';
        $this->message->setMsgContent($content);
        $this->assertSame($content, $this->message->getMsgContent());
    }

    public function testGetSetTitle(): void
    {
        $title = '测试标题';
        $this->message->setTitle($title);
        $this->assertSame($title, $this->message->getTitle());

        $this->message->setTitle(null);
        $this->assertNull($this->message->getTitle());
    }

    public function testGetSetContentType(): void
    {
        $type = 'text/plain';
        $this->message->setContentType($type);
        $this->assertSame($type, $this->message->getContentType());

        $this->message->setContentType(null);
        $this->assertNull($this->message->getContentType());
    }

    public function testGetSetExtras(): void
    {
        $extras = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 123,
        ];
        $this->message->setExtras($extras);
        $this->assertSame($extras, $this->message->getExtras());

        $this->message->setExtras(null);
        $this->assertNull($this->message->getExtras());
    }

    public function testToArrayWithMinimalFields(): void
    {
        $content = '测试消息内容';
        $this->message->setMsgContent($content);

        $data = $this->message->toArray();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('msg_content', $data);
        $this->assertSame($content, $data['msg_content']);

        // 可选字段不应存在
        $this->assertArrayNotHasKey('title', $data);
        $this->assertArrayNotHasKey('content_type', $data);
        $this->assertArrayNotHasKey('extras', $data);
    }

    public function testToArrayWithAllFields(): void
    {
        $content = '测试消息内容';
        $title = '测试标题';
        $type = 'text/plain';
        $extras = ['key1' => 'value1', 'key2' => 123];

        $this->message->setMsgContent($content);
        $this->message->setTitle($title);
        $this->message->setContentType($type);
        $this->message->setExtras($extras);

        $data = $this->message->toArray();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('msg_content', $data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('content_type', $data);
        $this->assertArrayHasKey('extras', $data);

        $this->assertSame($content, $data['msg_content']);
        $this->assertSame($title, $data['title']);
        $this->assertSame($type, $data['content_type']);
        $this->assertSame($extras, $data['extras']);
    }

    public function testToArrayOmitsNullFields(): void
    {
        $content = '测试消息内容';
        $title = '测试标题';

        $this->message->setMsgContent($content);
        $this->message->setTitle($title);
        // contentType和extras保持为null

        $data = $this->message->toArray();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('msg_content', $data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayNotHasKey('content_type', $data);
        $this->assertArrayNotHasKey('extras', $data);

        $this->assertSame($content, $data['msg_content']);
        $this->assertSame($title, $data['title']);
    }
}
