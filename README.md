# Jiguang Push Bundle

[![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://packagist.org/packages/tourze/jiguang-push-bundle)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)]()
[![Test Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)]()

## Introduction

Jiguang Push Bundle is a Symfony bundle for integrating Jiguang (JPush) push notification service. It provides a complete solution for managing push accounts, devices, tags, and sending push notifications to various platforms (iOS, Android, etc.).

## Features

- Manage multiple Jiguang push accounts
- Device registration and management
- Tag management and device grouping
- Flexible audience targeting (all, tag, alias, registrationId, etc.)
- Support for notification and message types
- Embedded entity support for advanced push features (options, callback, etc.)
- Symfony ORM integration
- Easy to extend and customize

## Installation

### Requirements

- PHP >= 8.1
- Symfony >= 6.4
- Doctrine ORM >= 2.20

### Install via Composer

```bash
composer require tourze/jiguang-push-bundle
```

## Quick Start

1. Register the bundle in your Symfony application (if not using Flex):

   ```php
   // config/bundles.php
   return [
       // ...
       JiguangPushBundle\JiguangPushBundle::class => ['all' => true],
   ];
   ```

2. Configure your Jiguang push accounts in the database or via admin panel.

3. Use the provided service to send push notifications:

   ```php
   use JiguangPushBundle\Service\JiguangService;
   use JiguangPushBundle\Request\PushRequest;
   use JiguangPushBundle\Entity\Push;

   $push = new Push();
   // ...set push properties...
   $request = new PushRequest();
   $request->setAccount($account);
   $request->setMessage($push);
   $service = $container->get(JiguangService::class);
   $result = $service->request($request);
   ```

4. For more usage, see entity and workflow docs in this package.

## Documentation

- [Entity Design](./ENTITY_DESIGN.md)
- [Workflow](./WORKFLOW.md)
- JPush API: <https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push>

## License

MIT
