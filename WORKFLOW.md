# Workflow / 工作流程（Mermaid）

```mermaid
flowchart TD
    A[Create Push Entity] --> B[Set Audience, Notification, etc.]
    B --> C[Create PushRequest]
    C --> D[Set Account and Message]
    D --> E[Call JiguangService->request()]
    E --> F[Format and Send HTTP Request]
    F --> G[JPush Server]
    G --> H[Response]
    H --> I[Format Response]
```

- 步骤说明：
  1. 创建推送消息实体（Push）并设置推送目标、内容等参数。
  2. 构建请求对象（PushRequest），设置账号和消息。
  3. 调用服务（JiguangService）发起推送。
  4. 服务内部组装 HTTP 请求，发送到极光服务器。
  5. 接收服务器响应并格式化返回。

> For more details, refer to the entity design and source code.
