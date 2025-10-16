# Changelog

[Русский](CHANGELOG.md) | [Deutsch](CHANGELOG.de.md) | [Français](CHANGELOG.fr.md)

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [2.0.0] - 2025-10-16

### 🎉 Major Release - Advanced Features & Multilingual Support

### Added

#### New Features
- ✨ **PHP 8+ Attributes** — #[Service], #[Inject], #[Tag] for declarative configuration
- ✨ **Decorator Priorities** — controlled decorator application order
- ✨ **Service Locator Pattern** — limited service access
- ✨ **Container Delegation** — search in multiple containers
- ✨ **Scoped Containers** — lifecycle management (request, session, etc.)
- ✨ **Async Service Initialization** — generator-based batch loading
- ✨ **Compiled Container with Tags** — embedded tag mappings
- ✨ **WeakMap Optimization** — zero memory leaks for lazy loading

#### New Classes
- `src/Attribute/Service.php` — attribute for auto-registration
- `src/Attribute/Inject.php` — attribute for explicit injection
- `src/Attribute/Tag.php` — attribute for tagging
- `src/ServiceLocator.php` — Service Locator pattern implementation
- `src/DelegatingContainer.php` — delegation to other containers
- `src/ScopedContainer.php` — scope support
- `src/ContainerExtensions.php` — trait with extended methods

#### Documentation
- 📖 Complete documentation in 4 languages (RU, EN, DE, FR)
- 📊 Detailed test reports in 4 languages (15 files)
- 💡 Usage examples in 4 languages
- 📁 Organized structure: `reports/`, `documentation/`, `examples/`

#### Tests
- ✅ Compiled Container Load Tests (5 tests)
- ✅ Compiled Container Stress Tests (5 tests)
- ✅ Extended autowiring coverage (11 tests)
- ✅ Tests for all new features

### Changed

#### Performance Improvements
- 🚀 **+1.3%** compiled container speed
- 🚀 **-47%** compiled container load time
- 🚀 **-17%** memory usage in compiled
- 🚀 WeakMap instead of array for lazy proxy tracking

#### Optimizations
- Decorators now sorted by priority
- Compiled container includes tag mappings
- Improved `get()` method with delegation and scopes support
- Optimized `autowire()` with #[Inject] support

---

## [1.0.0] - 2025-10-15

### Added

- ✅ PSR-11 Container Interface implementation
- ✅ Service registration and retrieval
- ✅ Singleton pattern for services
- 🤖 Autowiring with recursive dependency resolution
- 🔄 Lazy loading via proxy pattern
- 🎨 Service decorators
- ⚡ Compiled container
- 🏷️ Tagged services
- ✅ Comprehensive test suite (38 tests)
- ✅ PHPStan level max, PSR-12 code style

---

[2.0.0]: https://github.com/zorinalexey/cloud-casstle-di-container/releases/tag/v2.0.0
[1.0.0]: https://github.com/zorinalexey/cloud-casstle-di-container/releases/tag/v1.0.0
[Unreleased]: https://github.com/zorinalexey/cloud-casstle-di-container/compare/v2.0.0...HEAD

