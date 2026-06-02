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

## 保持登录有效期

已将 Adminer 登录页“永久登录（Permanent login）”对应的 `adminer_permanent` Cookie 有效期从 1 个月调整为 1 年（365 天），不修改现有登录页面结构。
另外，`permanentLogin()` 已固定使用密钥：`Joyark_Adminer_Secret_2026_X7kP9mQvL2wR8tY5uZ3vB6nM9pQ2wE4rT7yU`。
