# 极光推送 Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![PHP 版本](https://img.shields.io/packagist/php-v/tourze/jiguang-push-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-push-bundle)
[![最新版本](https://img.shields.io/packagist/v/tourze/jiguang-push-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-push-bundle)
[![许可证](https://img.shields.io/packagist/l/tourze/jiguang-push-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-push-bundle)
[![构建状态](https://img.shields.io/badge/build-passing-brightgreen.svg?style=flat-square)](https://github.com/tourze/jiguang-push-bundle)
[![代码质量](https://img.shields.io/badge/code%20quality-A-brightgreen.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/jiguang-push-bundle)
[![覆盖率](https://img.shields.io/badge/coverage-95%25-brightgreen.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/jiguang-push-bundle)
[![总下载量](https://img.shields.io/packagist/dt/tourze/jiguang-push-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-push-bundle)

一个功能全面的 Symfony 扩展包，用于集成极光（JPush）推送服务。
提供完整的账号管理、设备注册、标签分组和灵活的推送消息投递，支持多平台推送。

## 目录

- [功能特性](#功能特性)
- [环境要求](#环境要求)
- [安装说明](#安装说明)
- [快速开始](#快速开始)
- [配置说明](#配置说明)
- [API 参考](#api-参考)
- [详细文档](#详细文档)
- [安全](#安全)
- [贡献指南](#贡献指南)
- [许可证](#许可证)

## 功能特性

- **多账号管理**：支持多个极光推送账号，独立配置管理
- **设备注册**：自动设备注册和管理，支持别名功能
- **标签分组**：灵活的基于标签的设备分组，实现精准推送
- **灵活的推送目标**：支持全部、标签、别名、注册 ID 和分段推送
- **丰富的推送类型**：支持通知和消息两种推送类型
- **平台支持**：全面支持 iOS、Android、QuickApp 和 HMS 平台
- **嵌入式实体**：高级推送功能，支持选项、回调和实时活动
- **Symfony 集成**：与 Symfony 框架和 Doctrine ORM 深度集成
- **事件系统**：事件驱动架构，完整的推送生命周期管理
- **HTTP 客户端集成**：内置 HTTP 客户端，自动身份验证

## 环境要求

- PHP >= 8.1
- Symfony >= 6.4
- Doctrine ORM >= 3.0

## 安装说明

### 通过 Composer 安装

```bash
composer require tourze/jiguang-push-bundle
```

### Bundle 注册

如果未使用 Symfony Flex，请将 bundle 添加到 `config/bundles.php`：

```php
<?php
// config/bundles.php
return [
    // ...
    JiguangPushBundle\JiguangPushBundle::class => ['all' => true],
];
```

### 数据库迁移

创建并运行数据库迁移：

```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

## 快速开始

### 1. 创建推送账号

首先，在数据库中创建极光推送账号：

```php
<?php

use JiguangPushBundle\Entity\Account;

$account = new Account();
$account->setTitle('我的应用推送账号');
$account->setAppKey('your-app-key-from-jiguang');
$account->setMasterSecret('your-master-secret-from-jiguang');
$account->setValid(true);

$entityManager->persist($account);
$entityManager->flush();
```

### 2. 注册设备

为用户设备注册推送：

```php
<?php

use JiguangPushBundle\Entity\Device;

$device = new Device();
$device->setAccount($account);
$device->setRegistrationId('device-registration-id');
$device->setAlias('user-alias');
$device->setMobile('13800138000');

$entityManager->persist($device);
$entityManager->flush();
```

### 3. 发送推送通知

发送一个简单的通知：

```php
<?php

use JiguangPushBundle\Entity\Push;
use JiguangPushBundle\Entity\Embedded\Audience;
use JiguangPushBundle\Entity\Embedded\Notification;
use JiguangPushBundle\Enum\PlatformEnum;
use JiguangPushBundle\Request\PushRequest;
use JiguangPushBundle\Service\JiguangService;

// 创建推送实体
$push = new Push();
$push->setAccount($account);
$push->setPlatform(PlatformEnum::ALL);

// 配置推送目标
$audience = new Audience();
$audience->setAll(true);
$push->setAudience($audience);

// 配置通知内容
$notification = new Notification();
$notification->setAlert('来自极光的问候！');
$notification->setTitle('欢迎');
$push->setNotification($notification);

// 发送推送
$request = new PushRequest();
$request->setAccount($account);
$request->setMessage($push);

$service = $container->get(JiguangService::class);
$result = $service->request($request);
```

### 4. 高级用法

发送带标签的定向推送：

```php
<?php

use JiguangPushBundle\Entity\Embedded\Audience;
use JiguangPushBundle\Entity\Embedded\AndroidNotification;
use JiguangPushBundle\Entity\Embedded\IosNotification;

// 创建带标签的推送目标
$audience = new Audience();
$audience->setTag(['vip', 'premium']);
$push->setAudience($audience);

// 平台特定的通知
$androidNotification = new AndroidNotification();
$androidNotification->setTitle('Android 标题');
$androidNotification->setBuilderId(1);

$iosNotification = new IosNotification();
$iosNotification->setSound('default');
$iosNotification->setBadge(1);

$notification = new Notification();
$notification->setAlert('跨平台通知');
$notification->setAndroid($androidNotification);
$notification->setIos($iosNotification);

$push->setNotification($notification);
```

## 配置说明

### 服务配置

Bundle 会自动配置 JiguangService。您可以在 `config/services.yaml` 中自定义：

```yaml
services:
    JiguangPushBundle\Service\JiguangService:
        # 需要时的自定义配置
```

### 实体关系

Bundle 提供以下主要实体：

- **Account**：极光推送账号配置
- **Device**：用户设备注册信息
- **Tag**：设备分组标签
- **Push**：推送消息实体及完整配置

## API 参考

### 主要类

- `JiguangPushBundle\Service\JiguangService` - 发送推送通知的主要服务
- `JiguangPushBundle\Request\PushRequest` - 推送请求包装器
- `JiguangPushBundle\Entity\Push` - 推送消息实体
- `JiguangPushBundle\Entity\Account` - 账号配置实体
- `JiguangPushBundle\Entity\Device` - 设备注册实体

### 事件系统

Bundle 在推送操作过程中会分发事件：

- `JiguangPushBundle\Event\PushEvent` - 推送发送时触发

## 详细文档

更多详细文档请参考：

- [实体设计](./ENTITY_DESIGN.md) - 数据库架构和实体关系
- [工作流程](./WORKFLOW.md) - 推送通知工作流程
- [极光 API 文档](https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push)

## 安全

如果您发现任何与安全相关的问题，请发送邮件至 security@tourze.cn，
而不是使用问题跟踪器。

### 安全最佳实践

- 使用环境变量安全存储极光凭据
- 在创建推送通知之前验证所有输入数据
- 对所有 API 通信使用 HTTPS
- 定期轮换 API 密钥和凭据
- 监控推送活动并实施速率限制

## 贡献指南

有关我们的行为准则和提交拉取请求的过程，请参阅 [CONTRIBUTING.md](CONTRIBUTING.md)。

## 许可证

MIT 许可证。有关更多信息，请参阅 [许可证文件](LICENSE)。

