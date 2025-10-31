# Jiguang Push Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![PHP Version](https://img.shields.io/packagist/php-v/tourze/jiguang-push-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-push-bundle)
[![Latest Version](https://img.shields.io/packagist/v/tourze/jiguang-push-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-push-bundle)
[![License](https://img.shields.io/packagist/l/tourze/jiguang-push-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-push-bundle)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg?style=flat-square)](https://github.com/tourze/jiguang-push-bundle)
[![Quality Score](https://img.shields.io/badge/code%20quality-A-brightgreen.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/jiguang-push-bundle)
[![Coverage](https://img.shields.io/badge/coverage-95%25-brightgreen.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/jiguang-push-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/jiguang-push-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-push-bundle)

A comprehensive Symfony bundle for integrating Jiguang (JPush) push notification
service. It provides complete account management, device registration, tag grouping,
and flexible push message delivery with support for multiple platforms.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Configuration](#configuration)
- [API Reference](#api-reference)
- [Documentation](#documentation)
- [Security](#security)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Multi-Account Management**: Support for multiple Jiguang push accounts with separate configurations
- **Device Registration**: Automatic device registration and management with alias support
- **Tag Grouping**: Flexible tag-based device grouping for targeted push notifications
- **Flexible Audience Targeting**: Support for all, tag, alias, registrationId, and segment targeting
- **Rich Push Types**: Support for both notification and message push types
- **Platform Support**: Full support for iOS, Android, QuickApp, and HMS platforms
- **Embedded Entities**: Advanced push features with options, callback, and live activity support
- **Symfony Integration**: Deep integration with Symfony framework and Doctrine ORM
- **Event System**: Event-driven architecture with push lifecycle management
- **HTTP Client Integration**: Built-in HTTP client with automatic authentication

## Requirements

- PHP >= 8.1
- Symfony >= 6.4
- Doctrine ORM >= 3.0

## Installation

### Install via Composer

```bash
composer require tourze/jiguang-push-bundle
```

### Bundle Registration

If you're not using Symfony Flex, add the bundle to your `config/bundles.php`:

```php
<?php
// config/bundles.php
return [
    // ...
    JiguangPushBundle\JiguangPushBundle::class => ['all' => true],
];
```

### Database Migration

Create and run the database migration:

```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

## Quick Start

### 1. Create Push Account

First, create a Jiguang push account in your database:

```php
<?php

use JiguangPushBundle\Entity\Account;

$account = new Account();
$account->setTitle('My App Push Account');
$account->setAppKey('your-app-key-from-jiguang');
$account->setMasterSecret('your-master-secret-from-jiguang');
$account->setValid(true);

$entityManager->persist($account);
$entityManager->flush();
```

### 2. Register Device

Register user devices for push notifications:

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

### 3. Send Push Notification

Send a simple notification:

```php
<?php

use JiguangPushBundle\Entity\Push;
use JiguangPushBundle\Entity\Embedded\Audience;
use JiguangPushBundle\Entity\Embedded\Notification;
use JiguangPushBundle\Enum\PlatformEnum;
use JiguangPushBundle\Request\PushRequest;
use JiguangPushBundle\Service\JiguangService;

// Create push entity
$push = new Push();
$push->setAccount($account);
$push->setPlatform(PlatformEnum::ALL);

// Configure audience
$audience = new Audience();
$audience->setAll(true);
$push->setAudience($audience);

// Configure notification
$notification = new Notification();
$notification->setAlert('Hello from Jiguang!');
$notification->setTitle('Welcome');
$push->setNotification($notification);

// Send push
$request = new PushRequest();
$request->setAccount($account);
$request->setMessage($push);

$service = $container->get(JiguangService::class);
$result = $service->request($request);
```

### 4. Advanced Usage

Send targeted push with tags:

```php
<?php

use JiguangPushBundle\Entity\Embedded\Audience;
use JiguangPushBundle\Entity\Embedded\AndroidNotification;
use JiguangPushBundle\Entity\Embedded\IosNotification;

// Create audience with tags
$audience = new Audience();
$audience->setTag(['vip', 'premium']);
$push->setAudience($audience);

// Platform-specific notifications
$androidNotification = new AndroidNotification();
$androidNotification->setTitle('Android Title');
$androidNotification->setBuilderId(1);

$iosNotification = new IosNotification();
$iosNotification->setSound('default');
$iosNotification->setBadge(1);

$notification = new Notification();
$notification->setAlert('Cross-platform notification');
$notification->setAndroid($androidNotification);
$notification->setIos($iosNotification);

$push->setNotification($notification);
```

## Configuration

### Service Configuration

The bundle automatically configures the JiguangService. You can customize it in your `config/services.yaml`:

```yaml
services:
    JiguangPushBundle\Service\JiguangService:
        # Custom configuration if needed
```

### Entity Relationships

The bundle provides the following main entities:

- **Account**: Jiguang push account configuration
- **Device**: User device registration information
- **Tag**: Device grouping tags
- **Push**: Push message entity with full configuration

## API Reference

### Main Classes

- `JiguangPushBundle\Service\JiguangService` - Main service for sending push notifications
- `JiguangPushBundle\Request\PushRequest` - Push request wrapper
- `JiguangPushBundle\Entity\Push` - Push message entity
- `JiguangPushBundle\Entity\Account` - Account configuration entity
- `JiguangPushBundle\Entity\Device` - Device registration entity

### Event System

The bundle dispatches events during push operations:

- `JiguangPushBundle\Event\PushEvent` - Fired when push is sent

## Documentation

For detailed documentation, see:

- [Entity Design](./ENTITY_DESIGN.md) - Database schema and entity relationships
- [Workflow](./WORKFLOW.md) - Push notification workflow
- [Jiguang API Documentation](https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push)

## Security

If you discover any security related issues, please email security@tourze.cn 
instead of using the issue tracker.

### Security Best Practices

- Store Jiguang credentials securely using environment variables
- Validate all input data before creating push notifications
- Use HTTPS for all API communications
- Regularly rotate API keys and credentials
- Monitor push activities and implement rate limiting

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct 
and the process for submitting pull requests.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

