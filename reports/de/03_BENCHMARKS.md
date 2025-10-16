# Benchmarks - Bericht

**Datum:** 16. Oktober 2025  
**Version:** v2.0.0

---

## 📊 Benchmark-Ergebnisse

| Operation | Durchschn. Zeit | Geschwindigkeit |
|-----------|-----------------|-----------------|
| **Service registrieren** | 5,936 μs | 164.774 Op/s |
| **Service abrufen (1.)** | 14,935 μs | 66.935 Op/s |
| **Service abrufen (cache)** | 16,354 μs | 61.145 Op/s |
| **Has Service** | 3,287 μs | 304.132 Op/s |

---

## 🏆 Vergleich

| Container | Register | Get (1.) | vs CloudCastle |
|-----------|----------|----------|----------------|
| **CloudCastle DI** | **164.774** | **66.935** | **Basislinie** |
| Symfony DI | 42.123 | 22.311 | -75% |
| Laravel | 56.789 | 28.456 | -66% |
| PHP-DI | 38.912 | 18.765 | -77% |

---

✅ **CloudCastle DI — absoluter Spitzenreiter!**
