<?php

namespace JiguangPushBundle\Tests\Unit\Request;

use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Request\WithAccountRequest;
use PHPUnit\Framework\TestCase;

class WithAccountRequestTest extends TestCase
{
    public function testGetSetAccount(): void
    {
        $account = new Account();
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_secret');
        
        $request = $this->getMockForAbstractClass(WithAccountRequest::class);
        $request->setAccount($account);
        
        $this->assertSame($account, $request->getAccount());
        $this->assertSame('test_app_key', $request->getAccount()->getAppKey());
        $this->assertSame('test_secret', $request->getAccount()->getMasterSecret());
    }
}