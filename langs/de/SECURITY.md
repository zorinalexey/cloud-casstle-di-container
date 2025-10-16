# 🔒 CloudCastle DI Container Sicherheitsrichtlinie

---

## 📋 Unterstützte Versionen

Wir bieten derzeit Sicherheitsupdates für die folgenden Versionen an:

| Version | Sicherheitsunterstützung |
|---------|--------------------------|
| 2.0.x   | ✅ Volle Unterstützung |
| 1.x.x   | ⚠️ Nur kritische Schwachstellen |
| < 1.0   | ❌ Nicht unterstützt |

**Empfehlung:** Verwenden Sie die neueste Version 2.0.x, um alle Sicherheitsupdates zu erhalten.

---

## 🔐 Sicherheitsbewertung

**CloudCastle DI Container v2.0.0:**

- **Sicherheitsbewertung:** A+ ⭐⭐⭐⭐⭐
- **Sicherheitstests:** 15/15 (100%)
- **Kritische Schwachstellen:** 0
- **OWASP Top 10:** Konform
- **Letzte Überprüfung:** 16. Oktober 2025

**Detaillierter Bericht:** Siehe [reports/de/08_SECURITY.md](reports/de/08_SECURITY.md)

---

## 🐛 Melden von Schwachstellen

Wir nehmen die Sicherheit von CloudCastle DI Container ernst und schätzen die Bemühungen der Community bei der Entdeckung und verantwortungsvollen Offenlegung von Schwachstellen.

### Wie man eine Schwachstelle meldet

**Erstellen Sie KEINE öffentlichen Issues für Sicherheitslücken!**

Melden Sie sie stattdessen privat mit einer der folgenden Methoden:

#### 1. GitHub Security Advisory (empfohlen)

1. Gehen Sie zu https://github.com/zorinalexey/cloud-casstle-di-container/security/advisories
2. Klicken Sie auf **"Report a vulnerability"**
3. Füllen Sie das Formular mit detaillierter Beschreibung aus

#### 2. E-Mail

Senden Sie eine detaillierte Beschreibung der Schwachstelle an:
- **Primär:** zorinalexey59292@gmail.com
- **Alternativ:** alex-4-17@yandex.ru

**Betreff:** `[SECURITY] CloudCastle DI - Schwachstellenbeschreibung`

#### 3. Telegram (für dringende Fälle)

- **Persönlicher Kontakt:** [@CloudCastle85](https://t.me/CloudCastle85)

---

## 📝 Was in Ihren Bericht aufzunehmen ist

Bitte geben Sie folgende Informationen an:

1. **Beschreibung der Schwachstelle:**
   - Art der Schwachstelle (Injection, DoS, Memory Leak, usw.)
   - Betroffene Komponenten
   - Potenzielle Auswirkungen

2. **Schritte zur Reproduktion:**
   - Minimales reproduzierbares Codebeispiel
   - PHP- und CloudCastle DI-Version
   - Umgebungskonfiguration

3. **Mögliche Lösung (falls bekannt):**
   - Vorschläge zur Behebung
   - Patches oder Pull Requests (privat)

4. **Schweregrad:**
   - Critical (Kritisch)
   - High (Hoch)
   - Medium (Mittel)
   - Low (Niedrig)

5. **Ihre Kontaktdaten für Rückmeldung**

---

## ⏱️ Bearbeitungsprozess

### Unsere Antwortzeit

| Stufe | Zeit | Aktion |
|-------|------|--------|
| **Empfangsbestätigung** | 24 Stunden | Wir bestätigen den Empfang Ihrer Meldung |
| **Erstbewertung** | 48 Stunden | Schweregrad bewerten und Problem reproduzieren |
| **Fix-Entwicklung** | 7 Tage | Erstellen und testen der Behebung |
| **Patch-Veröffentlichung** | 14 Tage | Sicherheitsupdate veröffentlichen |
| **Öffentliche Offenlegung** | 30 Tage | Informationen nach Patch veröffentlichen |

**Hinweis:** Die Zeitrahmen können je nach Komplexität der Schwachstelle variieren.

### Koordinierte Offenlegung

Wir folgen dem Prinzip der **Responsible Disclosure**:

1. Sie melden uns die Schwachstelle privat
2. Wir bestätigen und arbeiten an einer Behebung
3. Wir veröffentlichen einen Patch
4. Nach 30 Tagen (oder nach Vereinbarung) veröffentlichen wir Details

---

## 🏆 Anerkennung von Sicherheitsforschern

Wir schätzen den Beitrag von Sicherheitsforschern und bieten:

- ✅ **Öffentliche Danksagung** in CHANGELOG und Security Advisory
- ✅ **Erwähnung in CONTRIBUTORS.md**
- ✅ **Link zu Ihrem Profil** (GitHub, Website, nach Wunsch)

---

## 🛡️ Aktuelle Sicherheitsmaßnahmen

CloudCastle DI Container umfasst folgende Sicherheitsmaßnahmen:

### Schutz vor häufigen Bedrohungen

- ✅ **Code Injection** — strenge Typisierung und Validierung
- ✅ **Memory Overflow** — effizientes Speichermanagement
- ✅ **DoS Attacks** — optimierte Performance
- ✅ **Circular Dependencies** — automatische Erkennung
- ✅ **Type Confusion** — Typ-Durchsetzung
- ✅ **Memory Leaks** — getestet für 15M Zyklen
- ✅ **Deserialization** — sichere Handhabung

### Sicherheitstests

- **15 automatisierte Sicherheitstests**
- **48 Security Assertions**
- **OWASP Top 10 Konformität**
- **Regelmäßige Überprüfungen** bei jedem Commit über CI/CD

**Vollständiger Bericht:** [reports/de/08_SECURITY.md](reports/de/08_SECURITY.md)

---

## 📞 Kontakt

**Für Sicherheitsfragen:**

- **E-Mail:** zorinalexey59292@gmail.com
- **GitHub Security:** https://github.com/zorinalexey/cloud-casstle-di-container/security
- **Telegram:** [@CloudCastle85](https://t.me/CloudCastle85)

**Für allgemeine Fragen:**

- **Telegram-Kanal:** [@cloud_castle_news](https://t.me/cloud_castle_news)
- **GitHub Issues:** https://github.com/zorinalexey/cloud-casstle-di-container/issues
- **VK:** https://vk.com/leha_zorin

---

**Letzte Aktualisierung:** 16. Oktober 2025  
**Version:** 2.0.0

[Русский](../../SECURITY.md) | [English](../en/SECURITY.md) | [Français](../fr/SECURITY.md)

