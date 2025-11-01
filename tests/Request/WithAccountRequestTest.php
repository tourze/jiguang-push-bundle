<?php

namespace JiguangPushBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Request\WithAccountRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(WithAccountRequest::class)]
final class WithAccountRequestTest extends RequestTestCase
{
    public function testGetSetAccount(): void
    {
        $account = new Account();
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_secret');

        $request = new class extends WithAccountRequest {
            /** @return array<string, mixed> */
            public function toArray(): array
            {
                return [];
            }

            public function getRequestPath(): string
            {
                return '/test';
            }

            /**
             * @return array<string, mixed>
             */
            public function getRequestOptions(): array
            {
                return [];
            }
        };
        $request->setAccount($account);

        $this->assertSame($account, $request->getAccount());
        $this->assertSame('test_app_key', $request->getAccount()->getAppKey());
        $this->assertSame('test_secret', $request->getAccount()->getMasterSecret());
    }
}
