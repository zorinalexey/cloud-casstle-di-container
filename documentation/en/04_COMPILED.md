# Compiled Container

**CloudCastle DI Container v2.0**

---

## 📋 Why Compile?

### Benefits

- ⚡ **+1.3% faster** get/has operations
- 🚀 **-47% load time**
- 💾 **-17% memory** per service
- 🏷️ **Embedded tags** — instant access
- 🔥 **Zero reflection** in production

---

## 🚀 Usage

### Method 1: Manual Compilation

```php
// 1. Configure container
$container = new Container();
$container->set('app', fn() => new App());

// 2. Compile
$code = $container->compile('MyContainer', 'App\\DI');

// 3. Save
file_put_contents(__DIR__ . '/cache/Container.php', $code);

// 4. Use
require __DIR__ . '/cache/Container.php';
$compiled = new \App\DI\MyContainer();
```

### Method 2: Via Composer (recommended)

```bash
# Auto-compiles after composer install/update
composer install

# Or manually
composer compile
```

---

## 📊 Performance

| Metric | Regular | Compiled | Improvement |
|--------|---------|----------|-------------|
| Get operation | 506,073 op/s | 512,628 op/s | **+1.3%** |
| Load time | 15 ms | 8 ms | **-47%** |
| Memory/service | 0.46 KB | 0.38 KB | **-17%** |

---

Next: [API Reference](05_API.md)

