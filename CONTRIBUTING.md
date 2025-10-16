# Участие в разработке CloudCastle DI

[English](CONTRIBUTING.en.md) | [Deutsch](CONTRIBUTING.de.md) | [Français](CONTRIBUTING.fr.md)

Спасибо за интерес к участию в разработке CloudCastle DI!

---

## 🛠️ Настройка окружения

1. Клонировать репозиторий
```bash
git clone https://github.com/zorinalexey/cloud-casstle-di-container.git
cd cloud-casstle-di-container
```

2. Установить зависимости
```bash
composer install
```

3. Запустить тесты
```bash
composer test
```

---

## 📋 Стандарты качества кода

Проект поддерживает высокие стандарты качества. Перед отправкой PR:

### 1. Запустить все проверки

```bash
make check
```

Или по отдельности:

```bash
composer analyse  # Статический анализ (PHPStan max level)
composer test     # Unit тесты (должно быть 100% pass)
composer phpmd    # Обнаружение проблем кода
```

### 2. Исправить стиль кода

```bash
make fix
```

Или:

```bash
composer phpcs:fix        # PSR-12 соответствие
composer php-cs-fixer:fix # Продвинутые исправления
composer rector:fix       # Автоматический рефакторинг
```

### 3. Покрытие тестами

- Поддерживайте высокое покрытие для новых фич
- Минимум 95% code coverage
- Запустите `composer test` для проверки

### 4. Обновить документацию

- Добавить PHPDoc блоки для всех публичных методов
- Обновить README.md при добавлении новых фич
- Обновить CHANGELOG.md
- Добавить примеры использования в `examples/`
- При необходимости обновить переводы (EN, DE, FR)

---

## 🔄 Процесс Pull Request

1. **Fork** репозитория
2. **Создать** feature branch: `git checkout -b feature/my-new-feature`
3. **Внести** изменения
4. **Запустить** все проверки: `make check`
5. **Исправить** проблемы: `make fix`
6. **Закоммитить**: `git commit -am 'Add new feature'`
7. **Запушить**: `git push origin feature/my-new-feature`
8. **Создать** Pull Request

### Требования к PR

- ✅ Все тесты проходят
- ✅ PHPStan level max без ошибок
- ✅ PSR-12 code style
- ✅ Обновлена документация
- ✅ Добавлены примеры (если применимо)
- ✅ Обновлён CHANGELOG.md

---

## 📏 Стандарты кодирования

### Общие правила

- Следовать PSR-12 coding style
- Использовать strict types: `declare(strict_types=1);`
- Писать comprehensive PHPDoc комментарии
- Type hint для всего (параметры и return types)
- Цель: zero PHPStan errors на max level

### Именование

- **Классы:** PascalCase (`Container`, `ServiceLocator`)
- **Методы:** camelCase (`get()`, `enableAutowiring()`)
- **Константы:** UPPER_CASE (`MAX_DEPTH`)
- **Приватные:** префикс не требуется

### Структура файлов

```php
<?php

declare(strict_types=1);

namespace CloudCastle\DI;

use CloudCastle\DI\Exception\ContainerException;
use Psr\Container\ContainerInterface;

/**
 * Class description.
 *
 * Detailed explanation if needed.
 */
final class MyClass implements MyInterface
{
    // Properties
    // Constructor
    // Public methods
    // Protected methods
    // Private methods
}
```

---

## 🧪 Тестирование

### Написание тестов

- Писать unit тесты для всего нового кода
- Следовать AAA pattern (Arrange, Act, Assert)
- Использовать описательные имена методов
- Одна assertion на тест (когда возможно)

### Типы тестов

1. **Unit Tests** (`tests/Unit/`)
   - Тестирование отдельных классов
   - Мокирование зависимостей
   - Быстрое выполнение

2. **Load Tests** (`tests/LoadTest.php`)
   - Тестирование под нагрузкой
   - 2M операций
   - Проверка утечек памяти

3. **Stress Tests** (`tests/StressTest.php`)
   - Экстремальные нагрузки
   - 15M операций
   - Поиск лимитов

4. **Benchmarks** (`benchmarks/`)
   - Сравнение производительности
   - PHPBench фреймворк

### Пример теста

```php
public function testMyNewFeature(): void
{
    // Arrange
    $container = new Container();
    $container->set('service', fn() => new MyService());
    
    // Act
    $result = $container->get('service');
    
    // Assert
    $this->assertInstanceOf(MyService::class, $result);
}
```

---

## 📊 Performance Guidelines

При добавлении новых фич:

- ✅ Минимизировать overhead
- ✅ Использовать кэширование где возможно
- ✅ Избегать ненужной reflection
- ✅ Профилировать с PHPBench
- ✅ Проверять утечки памяти

### Benchmark новых фич

```bash
# Добавить benchmark в benchmarks/
composer benchmark

# Проверить что производительность не упала
```

---

## 🐛 Reporting Issues

При создании issue укажите:

1. **Версию PHP** и CloudCastle DI
2. **Минимальный воспроизводимый пример**
3. **Ожидаемое поведение**
4. **Фактическое поведение**
5. **Stack trace** (если есть ошибка)

---

## 💡 Вопросы?

Не стесняйтесь открывать issue для вопросов или обсуждений.

---

## 🌟 Процесс релиза

1. Обновить версию в `composer.json`
2. Обновить `CHANGELOG.md`
3. Запустить все тесты
4. Создать тег: `git tag -a v2.x.x -m "Release v2.x.x"`
5. Запушить: `git push origin main --tags`

---

Спасибо за вклад в CloudCastle DI! 🎉
