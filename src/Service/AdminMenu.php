<?php

declare(strict_types=1);

namespace JiguangPushBundle\Service;

use JiguangPushBundle\Entity\Account;
use JiguangPushBundle\Entity\Device;
use JiguangPushBundle\Entity\Push;
use JiguangPushBundle\Entity\Tag;
use Knp\Menu\ItemInterface;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;

/**
 * 极光推送管理菜单服务
 */
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
    ) {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('极光推送')) {
            $item->addChild('极光推送');
        }

        $jiguangMenu = $item->getChild('极光推送');

        if (null === $jiguangMenu) {
            return;
        }

        // 账号管理菜单
        $jiguangMenu->addChild('账号管理')
            ->setUri($this->linkGenerator->getCurdListPage(Account::class))
            ->setAttribute('icon', 'fas fa-key')
        ;

        // 设备管理菜单
        $jiguangMenu->addChild('设备管理')
            ->setUri($this->linkGenerator->getCurdListPage(Device::class))
            ->setAttribute('icon', 'fas fa-mobile-alt')
        ;

        // 标签管理菜单
        $jiguangMenu->addChild('标签管理')
            ->setUri($this->linkGenerator->getCurdListPage(Tag::class))
            ->setAttribute('icon', 'fas fa-tags')
        ;

        // 推送消息菜单
        $jiguangMenu->addChild('推送消息')
            ->setUri($this->linkGenerator->getCurdListPage(Push::class))
            ->setAttribute('icon', 'fas fa-paper-plane')
        ;
    }
}
