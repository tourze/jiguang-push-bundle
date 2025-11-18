<?php

declare(strict_types=1);

namespace JiguangPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use JiguangPushBundle\Controller\Admin\TagCrudController;
use JiguangPushBundle\Entity\Tag;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * 极光推送标签CRUD控制器测试
 * @internal
 */
#[CoversClass(TagCrudController::class)]
#[RunTestsInSeparateProcesses]
final class TagCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testValidationErrors(): void
    {
        $client = self::createAuthenticatedClient();

        // Test creating Tag without required fields
        $crawler = $client->request('GET', '/admin/jiguang-push/tag/new');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertTrue($client->getResponse()->isSuccessful());

        // Check if form exists
        self::assertGreaterThan(0, $crawler->filter('form[name="Tag"]')->count(), 'New page should contain Tag form');

        // Find submit button
        $submitButton = $crawler->filter('button[type="submit"], input[type="submit"]');
        self::assertGreaterThan(0, $submitButton->count(), 'Form should contain submit button');

        $form = $submitButton->form();

        // Submit form with empty required fields
        $crawler = $client->submit($form);

        // Should get validation error response
        self::assertEquals(422, $client->getResponse()->getStatusCode());

        // Check for validation error messages in invalid feedback elements
        $invalidFeedback = $crawler->filter('.invalid-feedback');
        if ($invalidFeedback->count() > 0) {
            $errorContent = $invalidFeedback->text();
            self::assertStringContainsString('should not be blank', $errorContent);
        } else {
            // Alternative: check for form error classes
            $formErrors = $crawler->filter('.is-invalid, .form-error');
            self::assertGreaterThan(0, $formErrors->count(), 'Form should display validation errors');
        }
    }

    protected function getEntityFqcn(): string
    {
        return Tag::class;
    }

    /** @return TagCrudController */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(TagCrudController::class);
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '关联账号' => ['关联账号'];
        yield '标签值' => ['标签值'];
        yield '设备数量' => ['设备数量'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield 'account' => ['account'];
        yield 'value' => ['value'];
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

        // Navigate to Tag CRUD
        $link = $crawler->filter('a[href*="TagCrudController"]')->first();
        if ($link->count() > 0) {
            $client->click($link->link());
            self::assertEquals(200, $client->getResponse()->getStatusCode());
        }
    }
}
