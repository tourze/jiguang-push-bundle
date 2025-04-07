<?php

namespace JiguangPushBundle\Request;

use HttpClientBundle\Request\ApiRequest;
use JiguangPushBundle\Entity\Account;

abstract class WithAccountRequest extends ApiRequest
{
    private Account $account;

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }
}
