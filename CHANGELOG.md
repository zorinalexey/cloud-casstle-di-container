# Changelog

[English](CHANGELOG.en.md) | [Deutsch](CHANGELOG.de.md) | [Français](CHANGELOG.fr.md)

Все значимые изменения в проекте документируются в этом файле.

Формат основан на [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
проект следует [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [2.0.0] - 2025-10-16

### 🎉 Major Release - Advanced Features & Multilingual Support

### Added

#### Новые возможности
- ✨ **PHP 8+ Attributes** — #[Service], #[Inject], #[Tag] для декларативной конфигурации
- ✨ **Decorator Priorities** — управляемый порядок применения декораторов
- ✨ **Service Locator Pattern** — ограниченный доступ к сервисам
- ✨ **Container Delegation** — поиск в нескольких контейнерах
- ✨ **Scoped Containers** — lifecycle management (request, session, etc.)
- ✨ **Async Service Initialization** — generator-based batch loading
- ✨ **Compiled Container with Tags** — встроенные tag mappings
- ✨ **WeakMap Optimization** — zero memory leaks для lazy loading

#### Новые классы
- `src/Attribute/Service.php` — атрибут для автоматической регистрации
- `src/Attribute/Inject.php` — атрибут для явного указания зависимости
- `src/Attribute/Tag.php` — атрибут для тегов
- `src/ServiceLocator.php` — реализация Service Locator pattern
- `src/DelegatingContainer.php` — делегирование к другим контейнерам
- `src/ScopedContainer.php` — поддержка scopes
- `src/ContainerExtensions.php` — trait с расширенными методами

#### Методы
- `Container::registerFromAttribute()` — регистрация из атрибута
- `Container::registerFromDirectory()` — сканирование директории
- `Container::decorate($id, $decorator, $priority)` — с поддержкой приоритетов
- `Container::addDelegate()` — добавление delegate контейнера
- `Container::setScoped()` — регистрация scoped сервиса
- `Container::beginScope()` / `endScope()` — управление scope
- `Container::createServiceLocator()` — создание локатора
- `Container::createServiceLocatorFromTag()` — локатор из тега
- `Container::getAsync()` — async инициализация
- `Container::batchGetAsync()` — batch async loading

#### Документация
- 📖 Полная документация на 4 языках (RU, EN, DE, FR)
- 📊 Детальные отчёты по тестам на 4 языках (15 файлов)
- 💡 Примеры использования на 4 языках
- 📁 Организованная структура: `reports/`, `documentation/`, `examples/`

#### Тесты
- ✅ Compiled Container Load Tests (5 тестов)
- ✅ Compiled Container Stress Tests (5 тестов)
- ✅ Расширенное покрытие autowiring (11 тестов)
- ✅ Тесты для всех новых фич

### Changed

#### Улучшения производительности
- 🚀 **+1.3%** скорость compiled container
- 🚀 **-47%** время загрузки compiled container
- 🚀 **-17%** потребление памяти в compiled
- 🚀 WeakMap вместо array для lazy proxy tracking

#### Оптимизации
- Декораторы теперь сортируются по приоритету
- Compiled container включает tag mappings
- Улучшен метод `get()` с поддержкой delegation и scopes
- Оптимизирован `autowire()` с поддержкой #[Inject]

#### Тестирование
- Увеличены параметры stress тестов
- Добавлены сравнения с конкурентами
- Детальные отчёты по всем тестам

### Fixed
- Обратная совместимость decorators (старый и новый формат)
- PHPStan errors в ContainerExtensions
- Compiled container syntax для match statements

---

## [1.0.0] - 2025-10-15

### Added

#### Базовая функциональность
- ✅ PSR-11 Container Interface реализация
- ✅ Регистрация и получение сервисов
- ✅ Singleton pattern для сервисов
- ✅ Factory функции с dependency injection

#### Autowiring
- 🤖 Автоматическое разрешение зависимостей
- 🤖 Рекурсивное разрешение зависимостей
- 🤖 Поддержка default values
- 🤖 Поддержка nullable параметров
- 🤖 Обнаружение circular dependencies

#### Lazy Loading
- 🔄 LazyProxy для отложенной инициализации
- 🔄 Прозрачное проксирование методов и свойств
- 🔄 Проверка инициализации

#### Decorators
- 🎨 Декорирование сервисов
- 🎨 Множественные декораторы
- 🎨 Chain of responsibility pattern

#### Compiled Container
- ⚡ Генерация оптимизированного PHP кода
- ⚡ Pre-computed service lookups
- ⚡ Match expressions для has/get
- ⚡ Автоматическая компиляция через composer hooks

#### Tagged Services
- 🏷️ Группировка сервисов по тегам
- 🏷️ Теги с атрибутами
- 🏷️ Поиск сервисов по тегам
- 🏷️ Множественные теги на сервис

#### Testing & Quality
- ✅ 38 Unit тестов (100% pass)
- ✅ PHPBench бенчмарки
- ✅ Load тесты (2M операций)
- ✅ Stress тесты (15M операций, 1.7M сервисов)
- ✅ PHPStan level max
- ✅ PSR-12 code style
- ✅ PHPMD quality checks

---

## [Unreleased]

### Планируется

- [ ] Fiber-based async initialization (PHP 8.1+)
- [ ] Attribute-based service discovery
- [ ] Container warmup optimization
- [ ] GraphQL integration
- [ ] OpenTelemetry support

---

[2.0.0]: https://github.com/zorinalexey/cloud-casstle-di-container/releases/tag/v2.0.0
[1.0.0]: https://github.com/zorinalexey/cloud-casstle-di-container/releases/tag/v1.0.0
[Unreleased]: https://github.com/zorinalexey/cloud-casstle-di-container/compare/v2.0.0...HEAD
