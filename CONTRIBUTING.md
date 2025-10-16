# Contributing to CloudCastle DI

Thank you for your interest in contributing to CloudCastle DI!

## Development Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Run tests: `composer test`

## Code Quality Standards

This project maintains high code quality standards. Before submitting a PR:

### 1. Run All Checks

```bash
make check
```

Or individually:

```bash
composer analyse  # Static analysis
composer test     # Unit tests
composer phpmd    # Mess detection
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

### 3. Ensure Test Coverage

- Maintain 100% code coverage for new features
- Run `composer test:coverage` to check

### 4. Update Documentation

- Add PHPDoc blocks for all public methods
- Update README.md if adding new features
- Update CHANGELOG.md

## Pull Request Process

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/my-new-feature`
3. Make your changes
4. Run all quality checks: `make check`
5. Fix any issues: `make fix`
6. Commit your changes: `git commit -am 'Add new feature'`
7. Push to the branch: `git push origin feature/my-new-feature`
8. Create a Pull Request

## Coding Standards

- Follow PSR-12 coding style
- Use strict types: `declare(strict_types=1);`
- Write comprehensive PHPDoc comments
- Type hint everything (parameters and return types)
- Aim for zero PHPStan errors at max level

## Testing

- Write unit tests for all new code
- Follow AAA pattern (Arrange, Act, Assert)
- Use descriptive test method names
- One assertion per test method (when possible)

## Questions?

Feel free to open an issue for any questions or discussions.

