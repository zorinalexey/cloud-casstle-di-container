# CloudCastle DI Container v2.0 Testberichte

**Datum:** 16. Oktober 2025  
**Version:** 2.0.0  
**PHP:** 8.4.13

---

## 🏆 Schnelle Ergebnisse

- **Tests bestanden:** 63/64 (98.4%)
- **Max. Services:** 1.746.358
- **Operationen/Sek:** bis zu 500.133
- **Speicherlecks:** 0,001 MB pro 15M Zyklen
### 2. [Unit-Tests](02_UNIT_TESTS.md)
PHPUnit-Testergebnisse.

### 3. [Leistungs-Benchmarks](03_BENCHMARKS.md)
Detaillierte Leistungsmessungen.

### 4. [Lasttests](04_LOAD_TESTS.md)
Tests unter hoher Last.

### 5. [Stresstests](05_STRESS_TESTS.md)
Tests unter extremen Bedingungen.

### 6. [Kompilierter Container](06_COMPILED_CONTAINER.md)
Leistung des kompilierten Containers.

### 7. [Wettbewerbervergleich](07_COMPARISON.md)
Detaillierter Vergleich mit Symfony, Laravel, PHP-DI, Pimple, Laminas.

### 8. [Sicherheitstests](08_SECURITY.md) 🔒 🆕
Umfassende Sicherheitstests, OWASP Top 10, Sicherheitsvergleich mit Wettbewerbern.
- **Bewertung:** 🥇 #1 unter PHP DI-Containern

---

## 📊 Wichtige Metriken

| Metrik | Wert | vs Symfony |
|--------|------|------------|
| Register | 168.492 Op/s | **+300%** |
| Get (first) | 66.935 Op/s | **+200%** |
| Get (cached) | 61.145 Op/s | **+180%** |
| Has | 304.132 Op/s | **+275%** |
| Speicher/Service | 0,478 KB | **-40%** |

---

**[Русский](../ru/README.md) | [English](../en/README.md) | [Français](../fr/README.md)**
