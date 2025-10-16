# 🛠️ Contributing

Vielen Dank für Ihr Interesse an der Mitwirkung bei CloudCastle DI Container!

Wir begrüßen Beiträge von der Community und schätzen jeden Vorschlag zur Verbesserung des Projekts.

---

## 📋 Wie man beiträgt

### 🐛 Bug Reports

1. **Bestehende Issues prüfen** — das Problem könnte bereits bekannt sein
2. **Neues Issue erstellen** mit detaillierter Beschreibung:
   - PHP und CloudCastle DI Version
   - Schritte zur Reproduktion
   - Erwartetes Verhalten
   - Tatsächliches Verhalten
   - Fehlerprotokolle (falls vorhanden)

### ✨ Feature Requests

1. **Idee diskutieren** in einem Issue mit Tag `enhancement`
2. **Problem beschreiben**, das die Funktion löst
3. **Lösung vorschlagen** mit Verwendungsbeispielen
4. **Auf Feedback warten** von Maintainern

### 🔧 Pull Requests

1. **Repository forken**
2. **Feature Branch erstellen**: `git checkout -b feature/amazing-feature`
3. **Code-Standards befolgen** (siehe unten)
4. **Tests hinzufügen** für neue Funktionalität
5. **Dokumentation aktualisieren** falls nötig
6. **Pull Request erstellen**

---

## 📏 Code Standards

### 🎯 PHP Code Style

Wir folgen dem **PSR-12** Standard:

```bash
# Code Style prüfen
composer phpcs

# Auto-Fix
composer phpcs:fix
```

### 🧪 Testing

**Immer Tests für neue Funktionalität hinzufügen:**

```bash
# Alle Tests ausführen
composer test

# Mit Coverage ausführen
composer test:coverage

# Sicherheitstests ausführen
composer test:security
```

### 📊 Performance

**Neue Features müssen Benchmarks bestehen:**

```bash
# Benchmarks ausführen
composer benchmark

# Load Tests
composer load-test

# Stress Tests
composer stress-test
```

---

## 🔍 Review Prozess

### ✅ Was wir prüfen

1. **Code Korrektheit** — Logik funktioniert korrekt
2. **Standards Einhaltung** — PSR-12, Typisierung
3. **Test Coverage** — neue Features sind getestet
4. **Performance** — keine Regressionen
5. **Dokumentation** — aktualisiert falls nötig
6. **Sicherheit** — keine Vulnerabilities

### ⏱️ Review Zeit

- **Einfache Änderungen:** 1-3 Tage
- **Komplexe Änderungen:** 3-7 Tage
- **Kritische Änderungen:** 1-2 Wochen

---

## 🏗️ Entwicklung

### 🚀 Umgebung einrichten

```bash
# Repository klonen
git clone https://github.com/zorinalexey/cloud-casstle-di-container.git
cd cloud-casstle-di-container

# Dependencies installieren
composer install

# Tests ausführen
composer test
```

### 📁 Projekt Struktur

```
src/                    # Quellcode
├── Attribute/          # PHP 8.4 Attribute
├── Container.php       # Hauptcontainer
├── CompiledContainer.php
└── ...

tests/                  # Tests
├── Unit/              # Unit Tests
├── SecurityTest.php   # Sicherheitstests
├── LoadTest.php       # Load Tests
└── ...

documentation/          # Dokumentation
├── ru/                # Russische Dokumentation
├── en/                # Englische Dokumentation
├── de/                # Deutsche Dokumentation
└── fr/                # Französische Dokumentation

reports/               # Test Berichte
├── ru/                # Russische Berichte
├── en/                # Englische Berichte
├── de/                # Deutsche Berichte
└── fr/                # Französische Berichte
```

### 🔧 Nützliche Befehle

```bash
# Statische Analyse
composer analyse

# Code Style fixen
composer php-cs-fixer:fix

# Qualitätsmetriken
composer metrics

# Container kompilieren
composer compile
```

---

## 📚 Dokumentation

### 📝 Dokumentation Updates

Beim Hinzufügen neuer Features **immer aktualisieren:**

1. **README.md** — Hauptdokumentation
2. **Dokumentation** in `documentation/` (alle Sprachen)
3. **Beispiele** in `examples/`
4. **API Dokumentation** in relevanten Dateien

### 🌍 Mehrsprachigkeit

Dokumentation wird in 4 Sprachen gepflegt:
- **Russisch** (primär)
- **Englisch**
- **Deutsch**
- **Französisch**

**Beim Hinzufügen neuer Dokumentation, Dateien in allen Sprachen erstellen.**

---

## 🏆 Beitragsanerkennung

### ✅ Was wir tun

- **Öffentliche Anerkennung** in CHANGELOG.md
- **Erwähnung in CONTRIBUTORS.md**
- **Link zu Ihrem Profil** (GitHub, Website)
- **Spezielle Badges** für aktive Contributors

### 🎯 Beitragstypen

- **🐛 Bug Reports** — Fehlermeldungen
- **✨ Feature Requests** — Funktionsvorschläge
- **🔧 Code Contributions** — Code
- **📚 Documentation** — Dokumentation
- **🧪 Testing** — Tests
- **🎨 Design** — Design/UI
- **🌍 Translation** — Übersetzungen
- **📢 Promotion** — Projektförderung

---

## 📞 Kommunikation

### 💬 Diskussion

- **GitHub Issues** — für Diskussion von Problemen und Features
- **Telegram Kanal** — [@cloud_castle_news](https://t.me/cloud_castle_news)
- **Email** — zorinalexey59292@gmail.com

### 🆘 Hilfe

Wenn Sie Fragen zum Entwicklungsprozess haben:

1. **Dokumentation prüfen** in `documentation/`
2. **In Issues suchen** — die Frage könnte bereits diskutiert worden sein
3. **Neues Issue erstellen** mit Tag `question`
4. **In Telegram schreiben** — [@CloudCastle85](https://t.me/CloudCastle85)

---

## ⚖️ Verhaltenskodex

### 🤝 Unsere Prinzipien

- **Respekt** — behandeln einander mit Respekt
- **Toleranz** — akzeptieren verschiedene Meinungen und Erfahrungen
- **Konstruktivität** — fokussieren auf Problemlösung
- **Offenheit** — begrüßen neue Ideen

### 🚫 Unakzeptables Verhalten

- Beleidigungen oder Diskriminierung
- Spam oder Flooding
- Privatsphäre-Verletzungen
- Jedes Verhalten, das unangenehme Atmosphäre schafft

---

## 📋 PR Checkliste

Vor dem Einreichen eines Pull Requests sicherstellen:

- [ ] Code folgt PSR-12
- [ ] Tests für neue Funktionalität hinzugefügt
- [ ] Alle Tests bestehen (`composer test`)
- [ ] Dokumentation aktualisiert
- [ ] Keine Performance-Regressionen
- [ ] Code bestand statische Analyse
- [ ] PR hat klare Beschreibung

---

## 🙏 Danke

Danke für Ihren Beitrag zu CloudCastle DI Container!

Jede Verbesserung macht das Projekt besser für die gesamte Community.

---

**Letzte Aktualisierung:** 16. Oktober 2025

[Русский](../../CONTRIBUTING.md) | [English](../en/CONTRIBUTING.md) | [Français](../fr/CONTRIBUTING.md)