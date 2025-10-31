<?php

declare(strict_types=1);

namespace JiguangPushBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use JiguangPushBundle\Entity\Account;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * 极光推送账号管理控制器
 * @extends AbstractCrudController<Account>
 */
#[AdminCrud(routePath: '/jiguang-push/account', routeName: 'jiguang_push_account')]
#[Autoconfigure(public: true)]
final class AccountCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Account::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('极光账号')
            ->setEntityLabelInPlural('极光账号管理')
            ->setPageTitle('index', '极光账号列表')
            ->setPageTitle('new', '新增极光账号')
            ->setPageTitle('edit', '编辑极光账号')
            ->setPageTitle('detail', '极光账号详情')
            ->setHelp('index', '管理极光推送服务的账号配置信息')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['title', 'appKey'])
            ->setPaginatorPageSize(20)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->setMaxLength(9999)
            ->hideOnForm()
            ->setHelp('账号唯一标识')
        ;

        yield TextField::new('title', '账号标题')
            ->setRequired(true)
            ->setMaxLength(100)
            ->setHelp('账号的描述性名称，便于识别')
        ;

        yield TextField::new('appKey', '应用密钥')
            ->setRequired(true)
            ->setMaxLength(64)
            ->setHelp('极光推送应用的App Key')
            ->hideOnIndex()
        ;

        yield TextField::new('masterSecret', '主密钥')
            ->setRequired(true)
            ->setMaxLength(128)
            ->setHelp('极光推送应用的Master Secret')
            ->hideOnIndex()
        ;

        yield BooleanField::new('valid', '是否有效')
            ->setHelp('标记该账号配置是否有效可用')
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
            ->setHelp('账号创建时间')
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
            ->setHelp('最后更新时间')
        ;

        yield TextField::new('createBy', '创建人')
            ->hideOnForm()
            ->hideOnIndex()
            ->setHelp('创建该记录的用户')
        ;

        yield TextField::new('updateBy', '更新人')
            ->hideOnForm()
            ->hideOnIndex()
            ->setHelp('最后更新该记录的用户')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setLabel('查看详情');
            })
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('title', '账号标题'))
            ->add(TextFilter::new('appKey', '应用密钥'))
            ->add(BooleanFilter::new('valid', '是否有效'))
        ;
    }
}
