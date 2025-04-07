<?php

namespace JiguangPushBundle\Request;

use JiguangPushBundle\Entity\Push;

/**
 * @see https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push
 */
class PushRequest extends WithAccountRequest
{
    public function getRequestPath(): string
    {
        return 'https://api.jpush.cn/v3/push';
    }

    public function getRequestOptions(): ?array
    {
        return [
            'json' => $this->getMessage()->toArray(),
        ];
    }

    private Push $message;

    public function getMessage(): Push
    {
        return $this->message;
    }

    public function setMessage(Push $message): void
    {
        $this->message = $message;
    }
}
