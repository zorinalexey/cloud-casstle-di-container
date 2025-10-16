# Development Tools Documentation

This document describes all quality assurance tools configured in this project.

## Static Analysis Tools

### PHPStan

**Purpose**: Maximum level static analysis for type safety

**Configuration**: `phpstan.neon`

**Features**:
- Level: max (strictest)
- Strict rules enabled
- Deprecation detection
- Missing type checks
- Uninitialized property detection

**Usage**:
```bash
composer phpstan
```

## Code Style Tools

### PHP_CodeSniffer

**Purpose**: Detect PSR-12 coding standard violations

**Standard**: PSR-12

**Usage**:
```bash
# Check
composer phpcs

# Auto-fix
composer phpcs:fix
```

### PHP-CS-Fixer

**Purpose**: Advanced code style fixing

**Configuration**: `.php-cs-fixer.php`

**Features**:
- PSR-12 compliance
- PHP 8.1 migration rules
- Strict types declaration
- Import ordering
- PHPDoc standardization
- Trailing commas in arrays
- Many more rules

**Usage**:
```bash
# Dry run
composer php-cs-fixer

# Fix
composer php-cs-fixer:fix
```

## Code Quality Tools

### PHPMD (PHP Mess Detector)

**Purpose**: Detect code smells and potential problems

**Rulesets**:
- cleancode
- codesize
- controversial
- design
- naming
- unusedcode

**Usage**:
```bash
composer phpmd
```

### Rector

**Purpose**: Automated code refactoring and modernization

**Configuration**: `rector.php`

**Features**:
- PHP 8.1 syntax upgrades
- Code quality improvements
- Coding style improvements
- Dead code removal
- Type declaration additions
- Early returns
- Instance of simplification

**Usage**:
```bash
# Dry run
composer rector

# Apply changes
composer rector:fix
```

## Testing Tools

### PHPUnit

**Purpose**: Unit and integration testing

**Configuration**: `phpunit.xml`

**Features**:
- Separate test suites (Unit, Integration)
- Code coverage reports (HTML + text)
- Strict mode
- JUnit XML logging

**Usage**:
```bash
# Run tests
composer test

# With coverage
composer test:coverage
```

### PHPBench

**Purpose**: Performance benchmarking

**Configuration**: `phpbench.json`

**Features**:
- Iterations: 5
- Revolutions: 10,000
- Statistical analysis
- Memory usage tracking

**Usage**:
```bash
composer benchmark
```

### Load Testing

**Purpose**: High-volume performance testing

**Features**:
- 2M operations tested
- Memory leak detection
- Throughput measurement
- Performance degradation monitoring

**Usage**:
```bash
composer load-test
```

### Stress Testing

**Purpose**: Extreme conditions testing

**Features**:
- 15M operations tested
- Maximum service capacity
- Deep dependency chains (15K levels)
- Memory stability testing

**Usage**:
```bash
composer stress-test
```

## Metrics & Documentation

### PHPMetrics

**Purpose**: Code complexity and quality metrics

**Features**:
- Cyclomatic complexity
- Maintainability index
- Coupling metrics
- HTML report
- Code violations detection

**Usage**:
```bash
composer metrics
```

Output: `build/metrics/index.html`

## Quick Commands

### Check Everything
```bash
composer check
# Runs: analyse, test, phpmd
```

### Fix Everything
```bash
composer fix
# Runs: phpcs:fix, php-cs-fixer:fix, rector:fix
```

### Makefile Shortcuts
```bash
make test          # Run tests
make analyse       # Run all analyzers
make fix           # Fix code style
make metrics       # Generate metrics
make ci            # Full CI pipeline
make clean         # Clean generated files
```

## CI/CD Integration

GitHub Actions workflow (`.github/workflows/ci.yml`) runs:

1. **Tests** - Multiple PHP versions (8.1, 8.2, 8.3, 8.4)
2. **Static Analysis** - PHPStan, PHPMD
3. **Code Style** - PHP_CodeSniffer, PHP-CS-Fixer
4. **Performance Tests** - Benchmarks, Load Tests

## Recommended Workflow

### Before Committing
```bash
make fix    # Fix code style
make check  # Run all checks
```

### Before Pull Request
```bash
make ci              # Full CI pipeline
composer stress-test # Ensure performance under load
```

### Continuous Monitoring
```bash
composer metrics     # Check complexity metrics
composer benchmark   # Monitor performance
```

## Tool Versions

All tools are locked to specific major versions in `composer.json`:

- PHPUnit: ^10.5
- PHPStan: ^1.10
- PHP_CodeSniffer: ^3.8
- PHP-CS-Fixer: ^3.48
- PHPMD: ^2.15
- PHPMetrics: ^2.8
- PHPBench: ^1.2
- Rector: ^1.0

## Configuration Files Summary

| Tool | Configuration File |
|------|-------------------|
| PHPUnit | `phpunit.xml` |
| PHPStan | `phpstan.neon` |
| PHP-CS-Fixer | `.php-cs-fixer.php` |
| PHPBench | `phpbench.json` |
| Rector | `rector.php` |
| EditorConfig | `.editorconfig` |

