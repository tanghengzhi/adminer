# adminer

## Start

```bash
sudo docker compose up -d
```

## Stop

```bash
sudo docker compose down
```

## Upgarde

```bash
sudo docker compose pull
```

## 账号管理（信任设备）

通过 `login-servers` 插件在登录页展示常用连接（服务器）下拉列表，并配合浏览器“永久登录”实现信任设备记忆连接信息。

1. 在 `docker-compose.yml` 中配置 `ADMINER_LOGIN_SERVERS`（JSON 数组）：

```yaml
environment:
  - ADMINER_LOGIN_SERVERS=[{"name":"开发库","server":"mysql-dev:3306"},{"name":"测试库","server":"mysql-test:3306","driver":"pgsql"}]
```

2. 重启容器：

```bash
sudo docker compose up -d
```

3. 在 Adminer 登录页选择常用服务器，并在信任设备上勾选“永久登录（Permanent login）”。
