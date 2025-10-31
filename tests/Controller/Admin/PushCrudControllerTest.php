<?php

declare(strict_types=1);

namespace JiguangPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use JiguangPushBundle\Controller\Admin\PushCrudController;
use JiguangPushBundle\Entity\Push;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * 极光推送消息CRUD控制器测试
 * @internal
 */
#[CoversClass(PushCrudController::class)]
#[RunTestsInSeparateProcesses]
final class PushCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();

        // 访问创建页面
        $crawler = $client->request('GET', $this->generateAdminUrl('new'));
        $this->assertResponseIsSuccessful();

        // 查找表单并提交空表单
        $form = $crawler->selectButton('Create')->form();
        $crawler = $client->submit($form);

        // 验证返回状态码为422（验证错误）
        $this->assertResponseStatusCodeSame(422);

        // 验证错误消息存在（必填字段验证）
        $errorText = $crawler->filter('.invalid-feedback, .form-error-message, .error')->text();
        $this->assertStringContainsString('should not be null', $errorText, 'Account字段应该有必填验证错误');
    }

    protected function getEntityFqcn(): string
    {
        return Push::class;
    }

    /** @return PushCrudController */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(PushCrudController::class);
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '关联账号' => ['关联账号'];
        yield '推送平台' => ['推送平台'];
        yield '推送ID' => ['推送ID'];
        yield '消息ID' => ['消息ID'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield 'account' => ['account'];
        yield 'platform' => ['platform'];
        yield 'cid' => ['cid'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideEditPageFields(): iterable
    {
        return self::provideNewPageFields();
    }

    public function testIndexPage(): void
    {
        $client = self::createClientWithDatabase();
        $this->loginAsAdmin($client);

        $crawler = $client->request('GET', '/admin');
        self::assertEquals(200, $client->getResponse()->getStatusCode());

        // Navigate to Push CRUD
        $link = $crawler->filter('a[href*="PushCrudController"]')->first();
        if ($link->count() > 0) {
            $client->click($link->link());
            self::assertEquals(200, $client->getResponse()->getStatusCode());
        }
    }

    public function testGetEntityFqcn(): void
    {
        $this->assertSame(Push::class, PushCrudController::getEntityFqcn());
    }
}
