<?php

namespace JiguangPushBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use JiguangPushBundle\Entity\Push;
use JiguangPushBundle\Request\PushRequest;
use JiguangPushBundle\Service\JiguangService;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Push::class)]
class PushListener
{
    public function __construct(
        private readonly JiguangService $jiguangService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function postPersist(Push $push): void
    {
        if (null !== $push->getMsgId()) {
            return;
        }

        $request = new PushRequest();
        $request->setAccount($push->getAccount());
        $request->setMessage($push);
        $response = $this->jiguangService->request($request);
        if (is_array($response) && isset($response['msg_id'])) {
            $msgId = $response['msg_id'];
            if (is_string($msgId)) {
                $push->setMsgId($msgId);
                $this->entityManager->persist($push);
                $this->entityManager->flush();
            }
        }
    }
}
