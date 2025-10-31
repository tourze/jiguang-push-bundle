<?php

declare(strict_types=1);

namespace JiguangPushBundle\Tests\Service;

use JiguangPushBundle\Service\AdminMenu;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;

/**
 * 极光推送管理菜单服务测试
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private AdminMenu $adminMenu;

    protected function onSetUp(): void
    {
        $this->adminMenu = self::getService(AdminMenu::class);
    }

    public function testServiceImplementsMenuProviderInterface(): void
    {
        // self::getService 已经确保类型安全，无需额外断言
        $this->assertInstanceOf(MenuProviderInterface::class, $this->adminMenu);
    }

    public function testServiceIsCallable(): void
    {
        self::assertIsCallable($this->adminMenu);
    }
}
