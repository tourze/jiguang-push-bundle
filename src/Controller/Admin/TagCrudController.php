<?php

declare(strict_types=1);

namespace JiguangPushBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use JiguangPushBundle\Entity\Tag;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * 极光推送标签管理控制器
 * @extends AbstractCrudController<Tag>
 */
#[AdminCrud(routePath: '/jiguang-push/tag', routeName: 'jiguang_push_tag')]
#[Autoconfigure(public: true)]
final class TagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('推送标签')
            ->setEntityLabelInPlural('推送标签管理')
            ->setPageTitle('index', '推送标签列表')
            ->setPageTitle('new', '新增推送标签')
            ->setPageTitle('edit', '编辑推送标签')
            ->setPageTitle('detail', '推送标签详情')
            ->setHelp('index', '管理极光推送服务中的标签信息')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['value'])
            ->setPaginatorPageSize(20)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->setMaxLength(9999)
            ->hideOnForm()
            ->setHelp('标签记录唯一标识')
        ;

        yield AssociationField::new('account', '关联账号')
            ->setRequired(true)
            ->setHelp('该标签所属的极光推送账号')
        ;

        yield TextField::new('value', '标签值')
            ->setRequired(true)
            ->setMaxLength(40)
            ->setHelp('标签的具体值，用于设备分组和精准推送')
        ;

        yield AssociationField::new('devices', '关联设备')
            ->setFormTypeOptions([
                'by_reference' => false,
                'multiple' => true,
            ])
            ->setHelp('使用该标签的设备列表')
            ->hideOnIndex()
            ->onlyOnDetail()
        ;

        // 显示设备数量字段
        yield IntegerField::new('devicesCount', '设备数量')
            ->setHelp('使用该标签的设备数量')
            ->hideOnForm()
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
            ->setHelp('标签创建时间')
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
            ->setHelp('最后更新时间')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('account', '关联账号'))
            ->add(TextFilter::new('value', '标签值'))
        ;
    }
}
