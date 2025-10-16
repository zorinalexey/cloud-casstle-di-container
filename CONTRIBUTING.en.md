# 🛠️ Contributing

Thank you for your interest in contributing to CloudCastle DI Container!

We welcome contributions from the community and value every suggestion for improving the project.

---

## 📋 How to Contribute

### 🐛 Bug Reports

1. **Check existing issues** — the problem might already be known
2. **Create a new issue** with detailed description:
   - PHP and CloudCastle DI version
   - Steps to reproduce
   - Expected behavior
   - Actual behavior
   - Error logs (if any)

### ✨ Feature Requests

1. **Discuss the idea** in an issue tagged `enhancement`
2. **Describe the problem** the feature solves
3. **Propose a solution** with usage examples
4. **Wait for feedback** from maintainers

### 🔧 Pull Requests

1. **Fork the repository**
2. **Create a feature branch**: `git checkout -b feature/amazing-feature`
3. **Follow code standards** (see below)
4. **Add tests** for new functionality
5. **Update documentation** if necessary
6. **Create a Pull Request**

---

## 📏 Code Standards

### 🎯 PHP Code Style

We follow the **PSR-12** standard:

```bash
# Check code style
composer phpcs

# Auto-fix
composer phpcs:fix
```

### 🧪 Testing

**Always add tests for new functionality:**

```bash
# Run all tests
composer test

# Run with coverage
composer test:coverage

# Run security tests
composer test:security
```

### 📊 Performance

**New features must pass benchmarks:**

```bash
# Run benchmarks
composer benchmark

# Load tests
composer load-test

# Stress tests
composer stress-test
```

---

## 🔍 Review Process

### ✅ What We Check

1. **Code correctness** — logic works correctly
2. **Standards compliance** — PSR-12, typing
3. **Test coverage** — new features are tested
4. **Performance** — no regressions
5. **Documentation** — updated if necessary
6. **Security** — no vulnerabilities

### ⏱️ Review Time

- **Simple changes:** 1-3 days
- **Complex changes:** 3-7 days
- **Critical changes:** 1-2 weeks

---

## 🏗️ Development

### 🚀 Environment Setup

```bash
# Clone repository
git clone https://github.com/zorinalexey/cloud-casstle-di-container.git
cd cloud-casstle-di-container

# Install dependencies
composer install

# Run tests
composer test
```

### 📁 Project Structure

```
src/                    # Source code
├── Attribute/          # PHP 8.4 attributes
├── Container.php       # Main container
├── CompiledContainer.php
└── ...

tests/                  # Tests
├── Unit/              # Unit tests
├── SecurityTest.php   # Security tests
├── LoadTest.php       # Load tests
└── ...

documentation/          # Documentation
├── ru/                # Russian documentation
├── en/                # English documentation
├── de/                # German documentation
└── fr/                # French documentation

reports/               # Test reports
├── ru/                # Russian reports
├── en/                # English reports
├── de/                # German reports
└── fr/                # French reports
```

### 🔧 Useful Commands

```bash
# Static analysis
composer analyse

# Fix code style
composer php-cs-fixer:fix

# Quality metrics
composer metrics

# Compile container
composer compile
```

---

## 📚 Documentation

### 📝 Documentation Updates

When adding new features, **always update:**

1. **README.md** — main documentation
2. **Documentation** in `documentation/` (all languages)
3. **Examples** in `examples/`
4. **API documentation** in relevant files

### 🌍 Multilingual Support

Documentation is maintained in 4 languages:
- **Russian** (primary)
- **English**
- **German**
- **French**

**When adding new documentation, create files in all languages.**

---

## 🏆 Contribution Recognition

### ✅ What We Do

- **Public acknowledgment** in CHANGELOG.md
- **Mention in CONTRIBUTORS.md**
- **Link to your profile** (GitHub, website)
- **Special badges** for active contributors

### 🎯 Contribution Types

- **🐛 Bug Reports** — bug reports
- **✨ Feature Requests** — feature suggestions
- **🔧 Code Contributions** — code
- **📚 Documentation** — documentation
- **🧪 Testing** — tests
- **🎨 Design** — design/UI
- **🌍 Translation** — translations
- **📢 Promotion** — project promotion

---

## 📞 Communication

### 💬 Discussion

- **GitHub Issues** — for discussing problems and features
- **Telegram channel** — [@cloud_castle_news](https://t.me/cloud_castle_news)
- **Email** — zorinalexey59292@gmail.com

### 🆘 Help

If you have questions about the development process:

1. **Check documentation** in `documentation/`
2. **Search in issues** — the question might have been discussed
3. **Create a new issue** tagged `question`
4. **Write in Telegram** — [@CloudCastle85](https://t.me/CloudCastle85)

---

## ⚖️ Code of Conduct

### 🤝 Our Principles

- **Respect** — treat each other with respect
- **Tolerance** — accept different opinions and experience
- **Constructiveness** — focus on solving problems
- **Openness** — welcome new ideas

### 🚫 Unacceptable Behavior

- Insults or discrimination
- Spam or flooding
- Privacy violations
- Any behavior creating uncomfortable atmosphere

---

## 📋 PR Checklist

Before submitting a Pull Request, ensure:

- [ ] Code follows PSR-12
- [ ] Tests added for new functionality
- [ ] All tests pass (`composer test`)
- [ ] Documentation updated
- [ ] No performance regressions
- [ ] Code passed static analysis
- [ ] PR has clear description

---

## 🙏 Thank You

Thank you for contributing to CloudCastle DI Container!

Every improvement makes the project better for the entire community.

---

**Last Updated:** October 16, 2025

[Русский](CONTRIBUTING.md) | [Deutsch](CONTRIBUTING.de.md) | [Français](CONTRIBUTING.fr.md)