<?php

namespace JiguangPushBundle\Tests\Unit\DependencyInjection;

use JiguangPushBundle\DependencyInjection\JiguangPushExtension;
use JiguangPushBundle\Service\JiguangService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class JiguangPushExtensionTest extends TestCase
{
    public function testLoad(): void
    {
        $extension = new JiguangPushExtension();
        $container = new ContainerBuilder();
        
        $extension->load([], $container);
        
        // 检查是否有定义被加载
        $definitions = $container->getDefinitions();
        $this->assertGreaterThan(0, count($definitions));
        
        // 检查是否有我们期望的服务类
        $found = false;
        foreach ($definitions as $definition) {
            if ($definition->getClass() === JiguangService::class) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'JiguangService should be registered');
    }
}