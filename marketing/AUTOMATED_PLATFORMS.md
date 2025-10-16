# Платформы для автоматической публикации

---

## ✅ ГДЕ Я МОГУ ОПУБЛИКОВАТЬ АВТОМАТИЧЕСКИ

### 1. GitHub (✅ УЖЕ ОПУБЛИКОВАНО)
- ✅ Release v2.0.0
- ✅ Topics
- ✅ Description
- **API:** GitHub CLI (gh)
- **Статус:** 🟢 ГОТОВО

---

## 🤔 ГДЕ МОЖНО С API ТОКЕНОМ

Эти платформы имеют API, но нужны ваши токены:

### 2. Packagist (Composer)
- 📦 **Автоматическая синхронизация с GitHub**
- ✅ **Должно появиться автоматически** после первого `composer require`
- 🔗 https://packagist.org/packages/cloud-castle/di-container
- **Требуется:** регистрация пакета (один раз)

### 3. Telegram Channels
- 📱 Telegram Bot API
- **Требуется:** Bot Token (создать через @BotFather)
- **Рекомендуемые каналы:**
  - @php_legion
  - @web_dev_chat
  - @ru_php

### 4. Discord Webhooks
- 🎮 Discord Webhook URL
- **Требуется:** создать webhook в нужном канале
- **Рекомендуемые серверы:**
  - PHP Discord (#showcase)
  - Symfony Discord (#random)

### 5. Slack Webhooks  
- 💬 Slack Incoming Webhooks
- **Требуется:** Webhook URL
- **Рекомендуемые:**
  - Symfony Slack (#performance)
  - PHP Slack

---

## 📝 ГДЕ ТОЛЬКО ВРУЧНУЮ (НЕТ ПУБЛИЧНОГО API)

### Reddit
- ❌ Нет простого API для постов
- ⚠️ Требует OAuth + часто отклоняет
- 📝 Текст готов: `reddit_php_v2_discussion.txt`

### Habr
- ❌ Нет публичного API
- ✅ Но есть готовый текст: `habr_article.md`

### Dev.to
- ⚠️ Есть API, но требует токен
- 🔗 https://docs.dev.to/api/
- 📝 Текст готов: `devto_article.md`

### VK
- ⚠️ Есть API, но сложная аутентификация
- 📝 Текст готов: `vk_post.md`

### Twitter
- ❌ API платный (Twitter API v2)
- ❌ У вас заблокирован доступ

### LinkedIn
- ⚠️ Есть API, но требует OAuth
- ❌ У вас заблокирован доступ

---

## 🚀 ЧТО МОЖНО АВТОМАТИЗИРОВАТЬ ПРЯМО СЕЙЧАС

### А. Telegram (если есть bot)

Создам скрипт для публикации через Telegram Bot:

```bash
# Нужен Bot Token от @BotFather
# Нужен Channel ID

curl -X POST "https://api.telegram.org/bot<TOKEN>/sendMessage" \
  -d "chat_id=@channel_name" \
  -d "text=<post_text>" \
  -d "parse_mode=Markdown"
```

### Б. Discord Webhook

```bash
# Нужен Webhook URL

curl -X POST "https://discord.com/api/webhooks/<ID>/<TOKEN>" \
  -H "Content-Type: application/json" \
  -d '{"content": "<post_text>"}'
```

### В. Packagist

```bash
# Регистрация один раз
# Потом автосинхронизация с GitHub
```

---

## 💡 РЕКОМЕНДАЦИЯ

### Быстро и просто (БЕЗ токенов):

**Публикуйте вручную** на доступных платформах:
1. ✅ GitHub — УЖЕ ОПУБЛИКОВАНО
2. 📝 **Habr** — 5 минут (текст готов)
3. 📝 **VK** — 3 минуты (текст готов)
4. 📝 **Dev.to** — 3 минуты (текст готов)

**Итого: 11 минут** на все доступные платформы!

### С токенами (для полной автоматизации):

Если хотите полную автоматизацию, нужны:
- Telegram Bot Token (создать через @BotFather)
- Discord Webhook URL (Settings → Integrations → Webhooks)
- Dev.to API key (Settings → Account → DEV Community API Keys)

Я создам скрипты автоматической публикации для каждой платформы.

---

## 🎯 Хотите автоматизацию?

Скажите, для каких платформ вы хотите автоматические скрипты, 
и я создам их с инструкциями по получению токенов!

Варианты:
- [ ] Telegram каналы
- [ ] Discord серверы
- [ ] Dev.to (есть API)
- [ ] Packagist (автосинхронизация)

---

✨ Пока что: GitHub опубликован, остальные готовы к ручной публикации (11 минут)!
