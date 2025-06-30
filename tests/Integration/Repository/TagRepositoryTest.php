<?php

namespace JiguangPushBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use JiguangPushBundle\Entity\Tag;
use JiguangPushBundle\Repository\TagRepository;
use PHPUnit\Framework\TestCase;

class TagRepositoryTest extends TestCase
{
    public function testRepositoryClassName(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new TagRepository($registry);
        
        $this->assertInstanceOf(TagRepository::class, $repository);
    }

    public function testTagEntity(): void
    {
        $tag = new Tag();
        $tag->setValue('test_tag');
        
        $this->assertSame('test_tag', $tag->getValue());
    }
}