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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use JiguangPushBundle\Entity\Push;
use JiguangPushBundle\Enum\PlatformEnum;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminEnumFieldBundle\Field\EnumField;

/**
 * 极光推送消息管理控制器
 * @extends AbstractCrudController<Push>
 */
#[AdminCrud(routePath: '/jiguang-push/push', routeName: 'jiguang_push_push')]
#[Autoconfigure(public: true)]
final class PushCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Push::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('推送消息')
            ->setEntityLabelInPlural('推送消息管理')
            ->setPageTitle('index', '推送消息列表')
            ->setPageTitle('new', '新增推送消息')
            ->setPageTitle('edit', '编辑推送消息')
            ->setPageTitle('detail', '推送消息详情')
            ->setHelp('index', '管理极光推送服务的消息记录')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['cid', 'msgId'])
            ->setPaginatorPageSize(20)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->setMaxLength(9999)
            ->hideOnForm()
            ->setHelp('推送消息记录唯一标识')
        ;

        yield AssociationField::new('account', '关联账号')
            ->setRequired(true)
            ->setHelp('该推送消息所属的极光推送账号')
        ;

        $enumField = EnumField::new('platform', '推送平台')
            ->setHelp('目标推送平台')
        ;
        $enumField->setEnumCases(PlatformEnum::cases());
        yield $enumField->setRequired(true);

        yield TextField::new('cid', '推送ID')
            ->setMaxLength(100)
            ->setHelp('推送唯一标识，用于去重和追踪')
        ;

        yield TextField::new('msgId', '消息ID')
            ->setMaxLength(100)
            ->setHelp('推送成功后返回的消息ID')
            ->hideOnForm()
        ;

        // 嵌入对象字段简化处理 - 仅在详情页显示JSON格式数据
        yield TextareaField::new('audienceJson', '推送目标')
            ->setHelp('推送目标信息（JSON格式）')
            ->hideOnIndex()
            ->hideOnForm()
            ->onlyOnDetail()
            ->setFormTypeOptions([
                'mapped' => false,
            ])
            ->formatValue(function ($value, $entity) {
                if ($entity instanceof Push) {
                    return json_encode($entity->getAudience()->toData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }

                return '';
            })
        ;

        yield TextareaField::new('notificationJson', '通知内容')
            ->setHelp('通知消息内容（JSON格式）')
            ->hideOnIndex()
            ->hideOnForm()
            ->onlyOnDetail()
            ->setFormTypeOptions([
                'mapped' => false,
            ])
            ->formatValue(function ($value, $entity) {
                if ($entity instanceof Push) {
                    $notification = $entity->getNotification();
                    if (null !== $notification) {
                        return json_encode($notification->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    }
                }

                return '';
            })
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
            ->setHelp('推送消息创建时间')
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
            ->add(
                ChoiceFilter::new('platform', '推送平台')
                    ->setChoices([
                        '所有平台' => PlatformEnum::ALL->value,
                        'Android' => PlatformEnum::ANDROID->value,
                        'iOS' => PlatformEnum::IOS->value,
                        '快应用' => PlatformEnum::QUICKAPP->value,
                        '鸿蒙' => PlatformEnum::HMOS->value,
                    ])
            )
            ->add(TextFilter::new('cid', '推送ID'))
            ->add(TextFilter::new('msgId', '消息ID'))
        ;
    }
}
