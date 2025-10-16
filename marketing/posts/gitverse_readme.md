# Публикация на Gitverse

---

## 🇷🇺 Gitverse — Российская альтернатива GitHub

**URL:** https://gitverse.ru/

---

## 📋 Шаги для публикации

### 1. Создать аккаунт на Gitverse
- Зайти на https://gitverse.ru/
- Зарегистрироваться или войти

### 2. Создать новый репозиторий
- New Repository
- Имя: `cloud-castle-di-container`
- Описание: `High-performance PSR-11 DI Container for PHP 8.1+ | 3-4x faster than Symfony DI`
- Публичный
- Лицензия: MIT

### 3. Push кода из GitHub

```bash
cd /home/alexey/Документы/Projects/Cursor/DI

# Добавить Gitverse как remote
git remote add gitverse https://gitverse.ru/ваш-username/cloud-castle-di-container.git

# Или через SSH (если настроен)
# git remote add gitverse git@gitverse.ru:ваш-username/cloud-castle-di-container.git

# Отправить код
git push gitverse main

# Отправить теги (releases)
git push gitverse --tags
```

### 4. Настроить репозиторий

**Topics (теги):**
- php
- dependency-injection
- di-container
- psr-11
- performance
- php8
- ioc
- autowiring
- compiled-container

**README:**
Уже есть в репозитории на русском языке!

### 5. Создать Release

- Releases → New Release
- Tag: `v2.0.0`
- Заголовок: `CloudCastle DI v2.0.0 - High-Performance DI Container`
- Описание: Скопировать из `.github/release-notes-v2.0.0.md`

---

## 🎯 Преимущества Gitverse

1. **Российская аудитория** — разработчики из РФ
2. **Альтернатива GitHub** — резервная площадка
3. **Русскоязычное комьюнити**
4. **Поддержка импорта из GitHub**

---

## 📝 Описание для репозитория

```
CloudCastle DI Container — высокопроизводительный PSR-11 контейнер для PHP 8.1+

🏆 Рекордная производительность:
• 500,133 операций/сек под нагрузкой
• В 3-4 раза быстрее Symfony DI, Laravel Container, PHP-DI
• 0.001 МБ утечек памяти за 15M циклов
• 98.4% тестов пройдено

⚡ Возможности:
• PHP 8+ Attributes (#[Service], #[Inject], #[Tag])
• Autowiring с автоматическим разрешением зависимостей
• Lazy Loading с WeakMap оптимизацией
• Decorators с приоритетами
• Compiled Container (на 47% быстрее загрузка)
• Scoped Containers (request/session lifecycle)
• PSR-11 compliant

📦 Установка:
composer require cloud-castle/di-container

🔗 Ссылки:
• GitHub: https://github.com/zorinalexey/cloud-casstle-di-container
• Packagist: https://packagist.org/packages/cloud-castle/di-container
• Telegram: https://t.me/cloud_castle_news
• Документация: 4 языка (RU, EN, DE, FR)

🧪 Тестирование:
• Максимум: 1.7M сервисов без ошибок
• 15M операций стресс-тестирование
• Полное сравнение с 6 популярными контейнерами

Лицензия: MIT
```

---

## 🚀 После публикации

1. Добавить ссылку на Gitverse в README.md
2. Поделиться ссылкой на Habr и других площадках
3. Кросс-постинг обновлений между GitHub и Gitverse

---

✅ Готово к публикации!

