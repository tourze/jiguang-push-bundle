<?php

declare(strict_types=1);

namespace JiguangPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use JiguangPushBundle\Controller\Admin\AccountCrudController;
use JiguangPushBundle\Entity\Account;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * 极光推送账号CRUD控制器测试
 * @internal
 */
#[CoversClass(AccountCrudController::class)]
#[RunTestsInSeparateProcesses]
final class AccountCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();
        $crawler = $client->request('GET', $this->generateAdminUrl('new'));
        $this->assertResponseIsSuccessful();

        // 获取表单并设置无效数据来触发验证错误
        $form = $crawler->selectButton('Create')->form();
        $entityName = $this->getEntitySimpleName();

        // 设置无效数据来触发验证错误
        // title 字段保持空值应该触发验证错误（必填字段）
        // appKey 字段保持空值应该触发验证错误（必填字段）
        // masterSecret 字段保持空值应该触发验证错误（必填字段）
        $form[$entityName . '[title]'] = '';  // 空标题违反非空约束
        $form[$entityName . '[appKey]'] = '';  // 空AppKey违反非空约束
        $form[$entityName . '[masterSecret]'] = '';  // 空MasterSecret违反非空约束

        $crawler = $client->submit($form);

        // 验证返回状态码（422 Unprocessable Entity 或重定向到表单页面显示错误）
        if (422 === $client->getResponse()->getStatusCode()) {
            $this->assertResponseStatusCodeSame(422);
            // 检查是否有验证错误信息
            $errorText = $crawler->filter('.invalid-feedback, .form-error-message, .alert-danger')->text();
            self::assertNotEmpty($errorText, '应该显示验证错误信息');
        } else {
            // 如果不是422，可能是重定向回表单页面显示错误
            $this->assertResponseIsSuccessful();
            $errorElements = $crawler->filter('.invalid-feedback, .form-error-message, .alert-danger');
            self::assertGreaterThan(0, $errorElements->count(), '应该显示验证错误信息');
        }
    }

    protected function getEntityFqcn(): string
    {
        return Account::class;
    }

    /** @return AccountCrudController */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(AccountCrudController::class);
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '账号标题' => ['账号标题'];
        yield '是否有效' => ['是否有效'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield 'title' => ['title'];
        yield 'appKey' => ['appKey'];
        yield 'masterSecret' => ['masterSecret'];
        yield 'valid' => ['valid'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideEditPageFields(): iterable
    {
        return self::provideNewPageFields();
    }

    public function testIndexPage(): void
    {
        $client = self::createAuthenticatedClient();

        $crawler = $client->request('GET', '/admin');
        self::assertEquals(200, $client->getResponse()->getStatusCode());

        // Navigate to Account CRUD
        $link = $crawler->filter('a[href*="AccountCrudController"]')->first();
        if ($link->count() > 0) {
            $client->click($link->link());
            self::assertEquals(200, $client->getResponse()->getStatusCode());
        }
    }

    public function testGetEntityFqcn(): void
    {
        $this->assertSame(Account::class, AccountCrudController::getEntityFqcn());
    }
}
