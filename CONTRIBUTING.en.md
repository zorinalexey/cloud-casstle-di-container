# Contributing to CloudCastle DI

[Русский](CONTRIBUTING.md) | [Deutsch](CONTRIBUTING.de.md) | [Français](CONTRIBUTING.fr.md)

Thank you for your interest in contributing to CloudCastle DI!

---

## 🛠️ Development Setup

1. Clone the repository
```bash
git clone https://github.com/zorinalexey/cloud-casstle-di-container.git
cd cloud-casstle-di-container
```

2. Install dependencies
```bash
composer install
```

3. Run tests
```bash
composer test
```

---

## 📋 Code Quality Standards

This project maintains high code quality standards. Before submitting a PR:

### 1. Run All Checks

```bash
make check
```

Or individually:

```bash
composer analyse  # Static analysis (PHPStan max level)
composer test     # Unit tests (must be 100% pass)
composer phpmd    # Code mess detection
```

### 2. Fix Code Style

```bash
make fix
```

Or:

```bash
composer phpcs:fix        # PSR-12 compliance
composer php-cs-fixer:fix # Advanced fixes
composer rector:fix       # Automated refactoring
```

### 3. Test Coverage

- Maintain high coverage for new features
- Minimum 95% code coverage
- Run `composer test` to verify

### 4. Update Documentation

- Add PHPDoc blocks for all public methods
- Update README.md when adding new features
- Update CHANGELOG.md
- Add usage examples to `examples/`
- Update translations if necessary (RU, DE, FR)

---

## 🔄 Pull Request Process

1. **Fork** the repository
2. **Create** feature branch: `git checkout -b feature/my-new-feature`
3. **Make** your changes
4. **Run** all checks: `make check`
5. **Fix** issues: `make fix`
6. **Commit**: `git commit -am 'Add new feature'`
7. **Push**: `git push origin feature/my-new-feature`
8. **Create** Pull Request

### PR Requirements

- ✅ All tests pass
- ✅ PHPStan level max with no errors
- ✅ PSR-12 code style
- ✅ Documentation updated
- ✅ Examples added (if applicable)
- ✅ CHANGELOG.md updated

---

## 📏 Coding Standards

### General Rules

- Follow PSR-12 coding style
- Use strict types: `declare(strict_types=1);`
- Write comprehensive PHPDoc comments
- Type hint everything (parameters and return types)
- Aim for zero PHPStan errors at max level

### Testing

- Write unit tests for all new code
- Follow AAA pattern (Arrange, Act, Assert)
- Use descriptive test method names
- One assertion per test (when possible)

---

Thank you for contributing to CloudCastle DI! 🎉

