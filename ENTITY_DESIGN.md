# Entity Design / 实体设计说明

## Account (极光账号配置)

- `id`: int, 主键
- `title`: string, 账号名称
- `appKey`: string, 极光 AppKey，唯一
- `masterSecret`: string, 极光 MasterSecret
- `valid`: bool, 是否有效
- `createdBy`: string, 创建人
- `updatedBy`: string, 更新人
- `createTime`: datetime, 创建时间
- `updateTime`: datetime, 更新时间

## Device (设备信息)

- `id`: int, 主键
- `account`: Account, 所属极光账号
- `registrationId`: string, 设备注册ID，唯一
- `alias`: string, 设备别名
- `mobile`: string, 手机号
- `tags`: Tag[], 设备标签（多对多）
- `createTime`: datetime, 创建时间
- `updateTime`: datetime, 更新时间

## Tag (标签信息)

- `id`: int, 主键
- `account`: Account, 所属极光账号
- `value`: string, 标签值
- `devices`: Device[], 关联设备（多对多）
- `createTime`: datetime, 创建时间
- `updateTime`: datetime, 更新时间

## Push (推送消息)

- `id`: int, 主键
- `account`: Account, 所属极光账号
- `platform`: enum, 推送平台（iOS/Android/...）
- `audience`: Audience, 推送目标（all/tag/alias/registrationId/segment/abTest）
- `notification`: Notification, 通知内容
- `message`: Message, 消息内容
- `options`: Options, 推送选项
- `callback`: Callback, 回调设置
- `cid`: string, 推送ID
- `msgId`: string, 消息ID
- `createTime`: datetime, 创建时间
- `updateTime`: datetime, 更新时间

### Embedded Entities / 嵌入式实体

- **Audience**: 支持 all、tag、tagAnd、tagNot、alias、registrationId、segment、abTest
- **Notification**: 支持多平台通知内容（android、ios、hmos、quickapp、voip）
- **Message**: 消息内容、类型、标题、扩展字段
- **Options**: 离线保留、APNs 相关、第三方渠道、唯一标识等
- **Callback**: 回调地址、参数、类型

### 设计说明

- 各实体均支持创建/更新时间追踪
- 多对多关联采用 Doctrine Collection
- 支持灵活扩展和平台适配
- 详细字段和用法请参考源码注释

---

> English description and field mapping are inline above. For further extension, refer to the source code comments and Symfony ORM best practices.
