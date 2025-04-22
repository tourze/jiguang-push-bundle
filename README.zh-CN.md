# 极光推送 Bundle

[![版本](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://packagist.org/packages/tourze/jiguang-push-bundle)
[![构建状态](https://img.shields.io/badge/build-passing-brightgreen.svg)]()
[![测试覆盖率](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)]()

## 简介

极光推送 Bundle 是一个集成极光（JPush）推送服务的 Symfony 扩展包，支持多账号、设备、标签管理，灵活推送各类消息，支持 iOS、Android 等多平台。

## 功能特性

- 多极光账号管理
- 设备注册与管理
- 标签管理与分组
- 灵活的推送目标（全部、标签、别名、registrationId 等）
- 支持通知与消息两种类型
- 嵌入式实体，丰富推送高级功能（选项、回调等）
- 深度集成 Symfony ORM
- 易于扩展和定制

## 安装说明

### 环境要求

- PHP >= 8.1
- Symfony >= 6.4
- Doctrine ORM >= 2.20

### Composer 安装

```bash
composer require tourze/jiguang-push-bundle
```

## 快速开始

1. 注册 Bundle（如未使用 Flex）：

   ```php
   // config/bundles.php
   return [
       // ...
       JiguangPushBundle\JiguangPushBundle::class => ['all' => true],
   ];
   ```

2. 在数据库或后台配置极光推送账号。

3. 使用服务发送推送消息：

   ```php
   use JiguangPushBundle\Service\JiguangService;
   use JiguangPushBundle\Request\PushRequest;
   use JiguangPushBundle\Entity\Push;

   $push = new Push();
   // ...设置推送属性...
   $request = new PushRequest();
   $request->setAccount($account);
   $request->setMessage($push);
   $service = $container->get(JiguangService::class);
   $result = $service->request($request);
   ```

4. 更多用法请参考本包的实体设计与流程文档。

## 详细文档

- [实体设计说明](./ENTITY_DESIGN.md)
- [工作流程](./WORKFLOW.md)
- JPush 官方文档：<https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push>

## 许可证

MIT
