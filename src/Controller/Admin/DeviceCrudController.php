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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use JiguangPushBundle\Entity\Device;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * 极光推送设备管理控制器
 * @extends AbstractCrudController<Device>
 */
#[AdminCrud(routePath: '/jiguang-push/device', routeName: 'jiguang_push_device')]
#[Autoconfigure(public: true)]
final class DeviceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Device::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('推送设备')
            ->setEntityLabelInPlural('推送设备管理')
            ->setPageTitle('index', '推送设备列表')
            ->setPageTitle('new', '新增推送设备')
            ->setPageTitle('edit', '编辑推送设备')
            ->setPageTitle('detail', '推送设备详情')
            ->setHelp('index', '管理极光推送服务中的设备信息')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['registrationId', 'alias', 'mobile'])
            ->setPaginatorPageSize(20)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->setMaxLength(9999)
            ->hideOnForm()
            ->setHelp('设备记录唯一标识')
        ;

        yield AssociationField::new('account', '关联账号')
            ->setRequired(true)
            ->setHelp('该设备所属的极光推送账号')
        ;

        yield TextField::new('registrationId', '设备注册ID')
            ->setRequired(true)
            ->setMaxLength(64)
            ->setHelp('极光推送设备的唯一注册ID')
        ;

        yield TextField::new('alias', '设备别名')
            ->setMaxLength(120)
            ->setHelp('设备的别名，便于标识和推送')
        ;

        yield TextField::new('mobile', '手机号')
            ->setMaxLength(30)
            ->setHelp('与设备关联的手机号码')
        ;

        yield AssociationField::new('tags', '关联标签')
            ->setFormTypeOptions([
                'by_reference' => false,
                'multiple' => true,
            ])
            ->setHelp('该设备关联的标签列表')
            ->hideOnIndex()
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
            ->setHelp('设备记录创建时间')
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
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setLabel('查看详情');
            })
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('account', '关联账号'))
            ->add(TextFilter::new('registrationId', '设备注册ID'))
            ->add(TextFilter::new('alias', '设备别名'))
            ->add(TextFilter::new('mobile', '手机号'))
            ->add(EntityFilter::new('tags', '关联标签'))
        ;
    }
}
