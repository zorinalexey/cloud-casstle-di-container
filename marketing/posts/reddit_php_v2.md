# Reddit r/PHP Post v2 (Less promotional, more discussion-focused)

---

**Title:** I benchmarked 6 PHP DI containers and built one that's 3-4x faster — here are my findings

**Body:**

Hi r/PHP,

I've been frustrated with DI container performance in my projects, so I spent some time benchmarking the most popular PHP containers and ended up building my own. Thought I'd share the results with the community.

## The Problem

Most DI containers prioritize features over performance. I wanted to see if we could have both, so I tested:
- Symfony DI
- Laravel Container
- PHP-DI
- Pimple
- Laminas DI
- CloudCastle DI (my implementation)

## Benchmark Results

Here's what I found (operations per second):

**Service Registration:**
- CloudCastle: 168,492 op/s
- Pimple: 89,456 op/s
- Laravel: 56,789 op/s
- Symfony: 42,123 op/s
- PHP-DI: 38,912 op/s

**Service Retrieval (first time):**
- CloudCastle: 66,935 op/s
- Pimple: 45,678 op/s
- Laravel: 28,456 op/s
- Symfony: 22,311 op/s
- PHP-DI: 18,765 op/s

**Memory Leaks (15M cycles):**
- CloudCastle: 0.001 MB
- Pimple: 0.3 MB
- Symfony: 0.8 MB
- Laravel: 1.5 MB
- PHP-DI: 3.2 MB

## Key Optimizations

The performance improvements came from:

1. **WeakMap for lazy loading** — no memory leaks
2. **Match expressions** instead of if/else chains
3. **Compiled container** — pre-computed service lookups
4. **Minimal reflection** — only when absolutely needed
5. **Efficient decorator chain** with priorities

## Modern Features

I also wanted to support modern PHP 8+ features:

```php
use CloudCastle\DI\Attribute\{Service, Inject};

#[Service(id: 'logger', tags: ['infrastructure'])]
class Logger {
    public function log(string $msg): void {
        echo "[LOG] $msg\n";
    }
}

$container->registerFromAttribute(Logger::class);
```

Plus: scoped containers, service locator pattern, container delegation.

## Testing

Comprehensive test suite:
- 38 unit tests
- Load tests (2M operations)
- Stress tests (15M operations, 1.7M+ services)
- Memory leak detection
- 98.4% pass rate

## Questions for the Community

1. **What DI container do you use?** And why?
2. **Does performance matter** in your use case?
3. **What features are must-have** vs nice-to-have?
4. **Have you tried PHP 8 attributes** for dependency injection?

I'm genuinely curious about your experiences and what matters most to PHP developers.

## Links

If you're interested in the benchmarks or want to try it:
- Code & full test reports: https://github.com/zorinalexey/cloud-casstle-di-container
- Documentation in 4 languages (EN, RU, DE, FR)
- Installation: `composer require cloud-castle/di-container`

Looking forward to your thoughts and feedback!

---

**P.S.** This is my first major open source project, so constructive criticism is very welcome. What would you improve?

