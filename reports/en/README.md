# CloudCastle DI Container v2.0 Test Reports

**Date:** October 16, 2025  
**Version:** 2.0.0  
**PHP:** 8.4.13

---

## 📑 Contents

### 1. [Summary](01_SUMMARY.md)
Overall test results, key metrics, status.

### 2. [Unit Tests](02_UNIT_TESTS.md)
PHPUnit test results.

### 3. [Performance Benchmarks](03_BENCHMARKS.md)
Detailed performance measurements.

### 4. [Load Testing](04_LOAD_TESTS.md)
Tests under high load.

### 5. [Stress Testing](05_STRESS_TESTS.md)
Tests under extreme conditions.

### 6. [Compiled Container](06_COMPILED_CONTAINER.md)
Compiled container performance.

### 7. [Comparison with Competitors](07_COMPARISON.md)
Detailed comparison with Symfony, Laravel, PHP-DI, Pimple, Laminas.

### 8. [Security Tests](08_SECURITY.md) 🔒 🆕
Comprehensive security testing, OWASP Top 10, security comparison with competitors.

---

## 🏆 Quick Results

- **Tests passed:** 63/64 (98.4%)
- **Max services:** 1,746,359
- **Operations/sec:** up to 499,667
- **Memory leaks:** 0.001 MB per 15M cycles
- **Rating:** 🥇 #1 among PHP DI containers

---

## 📊 Key Metrics

| Metric | Value | vs Symfony |
|--------|-------|------------|
| Register | 164,774 op/s | **+300%** |
| Get (first) | 67,456 op/s | **+200%** |
| Get (cached) | 61,255 op/s | **+180%** |
| Has | 297,817 op/s | **+275%** |
| Memory/service | 0.478 KB | **-40%** |

---

**[Русский](../ru/README.md) | [Deutsch](../de/README.md) | [Français](../fr/README.md)**

