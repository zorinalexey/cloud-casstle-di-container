# Unit Tests - Report

**Date:** October 16, 2025  
**Version:** v2.0.0

---

## 📊 Results

**Tests:** 38  
**Passed:** 38 ✅  
**Failed:** 0  
**Time:** 0.109 seconds  
**Memory:** 12.00 MB

---

## ✅ Passed Tests

### Basic Functionality (8 tests)

1. ✅ **Can set and get service** — Service registration and retrieval
2. ✅ **Can set and get service with factory** — Factory support
3. ✅ **Service is singleton** — Singleton pattern works
4. ✅ **Has returns false for unregistered service** — Check non-existent
5. ✅ **Get throws exception for unregistered service** — Exception handling
6. ✅ **Can remove service** — Service removal
7. ✅ **Get service ids** — Get service list
8. ✅ **Factory receives container** — Factory gets container

### Autowiring (11 tests)

9. ✅ **Autowiring is disabled by default** — Disabled by default
10. ✅ **Can enable autowiring** — Enabling autowiring
11. ✅ **Autowiring simple class** — Simple class
12. ✅ **Autowiring with dependencies** — With dependencies
13. ✅ **Autowiring caches instances** — Caching
14. ✅ **Autowiring with registered dependencies** — With registered
15. ✅ **Autowiring detects circular dependencies** — Cycle detection
16. ✅ **Autowiring throws for non instantiable class** — Abstract classes
17. ✅ **Autowiring with default values** — Default values
18. ✅ **Autowiring with nullable parameters** — Nullable params
19. ✅ **Autowiring throws when disabled** — Exception when disabled

### Lazy Loading (5 tests)

20. ✅ **Lazy loading returns proxy** — Returns proxy
21. ✅ **Lazy service is not initialized until accessed** — Deferred init
22. ✅ **Lazy service proxy method calls** — Method proxying
23. ✅ **Lazy service proxy property access** — Property proxying
24. ✅ **Lazy service cached after initialization** — Caching after init

### Decorators (3 tests)

25. ✅ **Decorate service** — Basic decoration
26. ✅ **Multiple decorators** — Multiple decorators
27. ✅ **Decorate throws for non existent service** — Exception for non-existent

### Compiled Container (2 tests)

28. ✅ **Compile generates valid code** — Valid code generation
29. ✅ **Compile to file** — File saving

### Tagged Services (8 tests)

30. ✅ **Tag service** — Add tag
31. ✅ **Tag multiple services** — Multiple services
32. ✅ **Tag with multiple tags** — Multiple tags
33. ✅ **Tag with attributes** — Tags with attributes
34. ✅ **Find by tag** — Search by tag
35. ✅ **Get all tags** — Get all tags
36. ✅ **Untag service** — Remove tag
37. ✅ **Tag throws for non existent service** — Exception for non-existent
38. ✅ **Find by tag returns empty for unknown tag** — Empty result

---

## 📈 Feature Coverage

| Category | Covered | Percentage |
|----------|---------|------------|
| Basic operations | 8/8 | 100% |
| Autowiring | 11/11 | 100% |
| Lazy Loading | 5/5 | 100% |
| Decorators | 3/3 | 100% |
| Compilation | 2/2 | 100% |
| Tagged Services | 8/8 | 100% |
| **TOTAL** | **38/38** | **100%** |

---

## 🎯 Conclusions

✅ All unit tests passed successfully  
✅ 100% coverage of core functionality  
✅ All v2.0 features tested  
✅ No regressions detected

