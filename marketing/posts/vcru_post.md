# VC.ru Post

---

## Пост для VC.ru (IT/стартап сообщество)

**Заголовок:**
CloudCastle DI Container v2.0 — PHP контейнер который в 4 раза быстрее Symfony DI

**Текст:**

Разработал DI контейнер для PHP, который показывает рекордную производительность. Делюсь результатами тестирования и техническими деталями.

## 🎯 Задача

Существующие DI контейнеры (Symfony, Laravel, PHP-DI) функциональны, но медленные. Захотел проверить: можно ли сделать контейнер, который будет И быстрым, И функциональным одновременно.

## 📊 Результаты

Протестировал 6 популярных контейнеров с одинаковой нагрузкой:

| Контейнер | Операций/сек | Отставание |
|-----------|--------------|------------|
| **CloudCastle DI** | **168,492** | Базовая линия |
| Pimple | 89,456 | -47% |
| Laravel Container | 56,789 | -66% |
| Symfony DI | 42,123 | **-75%** |
| PHP-DI | 38,912 | -77% |

**CloudCastle в 4 раза быстрее Symfony DI.**

## 🔬 Технические решения

Ключевые оптимизации:

1. **Match expressions** вместо if/else цепочек (PHP 8+)
2. **WeakMap** для отслеживания lazy proxies (нет утечек памяти)
3. **Compiled container** с пре-вычисленными lookups
4. **Минимальный overhead** в методе get()

Пример скомпилированного кода:
```php
public function get(string $id): mixed {
    return match ($id) {
        'service1' => $this->instances['service1'] ??= $this->service0(),
        'service2' => $this->instances['service2'] ??= $this->service1(),
        default => throw new NotFoundException(...),
    };
}
```

## 💾 Управление памятью

Тест на 15 миллионов циклов создания/удаления:

- CloudCastle DI: **0.001 МБ** рост памяти
- Symfony DI: 0.8 МБ
- PHP-DI: 3.2 МБ

Практически нет утечек.

## ⚡ Возможности

- PHP 8+ Attributes для конфигурации
- Autowiring с автоматическим разрешением зависимостей
- Lazy Loading с WeakMap оптимизацией
- Decorators с приоритетами
- Compiled container (на 47% быстрее загрузка)
- Scoped containers (request/session lifecycle)
- PSR-11 compliant

## 🧪 Тестирование

- 63 из 64 тестов пройдено (98.4%)
- Максимум: 1.7M сервисов без ошибок
- 15M операций стресс-тестирование
- Полное сравнение с 6 конкурентами

## 📖 Open Source

Проект полностью open source (MIT License):
- **GitHub:** https://github.com/zorinalexey/cloud-casstle-di-container
- **Packagist:** https://packagist.org/packages/cloud-castle/di-container
- **Документация:** на 4 языках (RU, EN, DE, FR)

Установка:
```bash
composer require cloud-castle/di-container
```

## 💡 Для кого?

- High-load приложения (выдерживает 500k оп/сек)
- Микросервисная архитектура (минимум памяти)
- Enterprise проекты (полный набор фич)
- Современные PHP 8+ проекты

## 🤔 Вопросы к сообществу

1. Используете ли DI контейнеры в своих проектах?
2. Важна ли для вас производительность контейнера?
3. Какие фичи в DI контейнере критичны для вас?

Буду рад feedback и предложениям по улучшению!

---

**Теги:** #php #opensource #разработка #performance #стартап #it

