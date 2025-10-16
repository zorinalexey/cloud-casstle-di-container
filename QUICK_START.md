# Quick Start Guide

## Инициализация проекта

Библиотека CloudCastle\DI полностью инициализирована со всеми инструментами анализа, тестирования и документации.

## 🚀 Первые шаги

### 1. Установка зависимостей

```bash
composer install
```

### 2. Проверка работоспособности

```bash
# Запустить тесты
composer test

# Запустить анализаторы
composer analyse
```

## 📊 Доступные инструменты

### Анализаторы кода

| Инструмент | Команда | Описание |
|------------|---------|----------|
| **PHPStan** | `composer phpstan` | Статический анализ максимального уровня |
| **PHPMD** | `composer phpmd` | Детектор проблемного кода |
| **PHP_CodeSniffer** | `composer phpcs` | Проверка стиля PSR-12 |

### Инструменты тестирования

| Инструмент | Команда | Описание |
|------------|---------|----------|
| **PHPUnit** | `composer test` | Unit и интеграционные тесты |
| **Coverage** | `composer test:coverage` | Покрытие кода тестами |
| **PHPBench** | `composer benchmark` | Бенчмарки производительности |
| **LoadTest** | `composer load-test` | Нагрузочные тесты (2M операций) |
| **StressTest** | `composer stress-test` | Стресс-тесты (15M операций) |

### Генераторы документации и метрик

| Инструмент | Команда | Вывод |
|------------|---------|-------|
| **PHPMetrics** | `composer metrics` | Метрики кода в `build/metrics/` |

### Инструменты автоматизации

| Инструмент | Команда | Описание |
|------------|---------|----------|
| **PHP-CS-Fixer** | `composer php-cs-fixer:fix` | Автоисправление стиля кода |
| **Rector** | `composer rector:fix` | Автоматический рефакторинг |

## ⚡ Быстрые команды

```bash
# Запустить все проверки
composer check

# Исправить все автоматически
composer fix

# Полный CI процесс (через Makefile)
make ci

# Очистить сгенерированные файлы
make clean
```

## 📁 Структура проекта

```
CloudCastle/DI/
├── src/                    # Исходный код библиотеки
│   ├── Container.php       # Основной DI контейнер
│   └── Exception/          # Исключения
├── tests/                  # Тесты
│   ├── Unit/              # Юнит-тесты
│   └── Integration/       # Интеграционные тесты
├── benchmarks/            # Бенчмарки производительности
├── docs/                  # Документация
│   ├── ARCHITECTURE.md    # Описание архитектуры
│   ├── EXAMPLES.md        # Примеры использования
│   └── TOOLS.md           # Документация инструментов
└── [конфиги инструментов]
```

## 🔧 Конфигурационные файлы

### Статический анализ
- `phpstan.neon` - PHPStan (максимальный уровень)
- `.php-cs-fixer.php` - PHP-CS-Fixer (PSR-12 + расширенные правила)

### Тестирование
- `phpunit.xml` - PHPUnit
- `phpbench.json` - PHPBench

### Автоматизация
- `rector.php` - Rector (PHP 8.1+, автоулучшения)
- `Makefile` - Make команды
- `.github/workflows/ci.yml` - GitHub Actions CI

## 💡 Рекомендуемый workflow

### Перед коммитом
```bash
composer fix      # Исправить стиль
composer check    # Проверить качество
```

### Перед Pull Request
```bash
make ci              # Полный CI
composer stress-test # Проверка под нагрузкой
```

## 📖 Использование библиотеки

```php
use CloudCastle\DI\Container;

$container = new Container();

// Регистрация сервиса
$container->set('logger', function() {
    return new Logger('app.log');
});

// Получение сервиса
$logger = $container->get('logger');
```

Больше примеров: [docs/EXAMPLES.md](docs/EXAMPLES.md)

## 📚 Дополнительная документация

- [README.md](README.md) - Основная информация
- [ARCHITECTURE.md](docs/ARCHITECTURE.md) - Архитектура
- [EXAMPLES.md](docs/EXAMPLES.md) - Примеры использования
- [TOOLS.md](docs/TOOLS.md) - Подробная документация инструментов
- [CONTRIBUTING.md](CONTRIBUTING.md) - Руководство для разработчиков

## 🎯 Требования

- PHP ≥ 8.1
- Composer

## 📝 Лицензия

MIT License - см. [LICENSE](LICENSE)
