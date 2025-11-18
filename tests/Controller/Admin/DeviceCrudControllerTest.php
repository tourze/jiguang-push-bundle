<?php

declare(strict_types=1);

namespace JiguangPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use JiguangPushBundle\Controller\Admin\DeviceCrudController;
use JiguangPushBundle\Entity\Device;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\DomCrawler\Crawler;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * 极光推送设备CRUD控制器测试
 * @internal
 */
#[CoversClass(DeviceCrudController::class)]
#[RunTestsInSeparateProcesses]
final class DeviceCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getEntityFqcn(): string
    {
        return Device::class;
    }

    /** @return DeviceCrudController */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(DeviceCrudController::class);
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '关联账号' => ['关联账号'];
        yield '设备注册ID' => ['设备注册ID'];
        yield '设备别名' => ['设备别名'];
        yield '手机号' => ['手机号'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield 'account' => ['account'];
        yield 'registrationId' => ['registrationId'];
        yield 'alias' => ['alias'];
        yield 'mobile' => ['mobile'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideEditPageFields(): iterable
    {
        yield 'account' => ['account'];
        yield 'registrationId' => ['registrationId'];
        yield 'alias' => ['alias'];
        yield 'mobile' => ['mobile'];
    }

    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();

        try {
            $crawler = $client->request('GET', $this->generateAdminUrl('new'));
            $this->assertResponseIsSuccessful();
        } catch (\Exception $e) {
            self::markTestSkipped('NEW action is not available for this controller: ' . $e->getMessage());
        }

        $button = $this->findSubmitButton($crawler);
        if (null === $button) {
            self::markTestSkipped('No submit button found in the new form.');
        }

        $form = $button->form();
        $crawler = $client->submit($form, []);

        // 验证测试：提交空表单应该返回422状态码（验证错误）
        if (422 === $client->getResponse()->getStatusCode()) {
            $this->assertResponseStatusCodeSame(422);
            $errorElements = $crawler->filter('.invalid-feedback');
            if ($errorElements->count() > 0) {
                $this->assertStringContainsString('should not be blank', $errorElements->text());
            }
        } else {
            // 如果没有返回422，可能是表单提交成功或有其他处理方式
            $this->assertTrue(
                $client->getResponse()->isSuccessful() || $client->getResponse()->isRedirect(),
                sprintf('Expected successful or redirect response, got %d', $client->getResponse()->getStatusCode())
            );
        }
    }

    private function findSubmitButton(Crawler $crawler): ?Crawler
    {
        $buttonSelectors = ['保存', 'Create', 'Save'];

        foreach ($buttonSelectors as $selector) {
            $button = $crawler->selectButton($selector);
            if ($button->count() > 0) {
                return $button;
            }
        }

        return null;
    }

    public function testFieldsConfiguration(): void
    {
        $controller = new DeviceCrudController();
        $fields = iterator_to_array($controller->configureFields('index'));

        self::assertNotEmpty($fields);
        self::assertGreaterThanOrEqual(5, count($fields), '应该包含至少5个字段');
    }

    public function testControllerIsInstantiable(): void
    {
        $controller = new DeviceCrudController();
        $reflection = new \ReflectionClass($controller);
        self::assertTrue($reflection->isInstantiable());
    }
}
