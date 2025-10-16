# 🔒 CloudCastle DI Container Security Policy

---

## 📋 Supported Versions

We currently provide security updates for the following versions:

| Version | Security Support |
|---------|-----------------|
| 2.0.x   | ✅ Full support |
| 1.x.x   | ⚠️ Critical vulnerabilities only |
| < 1.0   | ❌ Not supported |

**Recommendation:** Use the latest 2.0.x version to receive all security updates.

---

## 🔐 Security Rating

**CloudCastle DI Container v2.0.0:**

- **Security Rating:** A+ ⭐⭐⭐⭐⭐
- **Security Tests:** 15/15 (100%)
- **Critical Vulnerabilities:** 0
- **OWASP Top 10:** Compliant
- **Last Review:** October 16, 2025

**Detailed Report:** See [reports/en/08_SECURITY.md](reports/en/08_SECURITY.md)

---

## 🐛 Reporting Vulnerabilities

We take the security of CloudCastle DI Container seriously and appreciate the community's efforts in discovering and responsibly disclosing vulnerabilities.

### How to Report a Vulnerability

**DO NOT create public issues for security vulnerabilities!**

Instead, please report them privately using one of the following methods:

#### 1. GitHub Security Advisory (recommended)

1. Go to https://github.com/zorinalexey/cloud-casstle-di-container/security/advisories
2. Click **"Report a vulnerability"**
3. Fill in the form with detailed description

#### 2. Email

Send a detailed description of the vulnerability to:
- **Primary:** zorinalexey59292@gmail.com
- **Alternative:** alex-4-17@yandex.ru

**Subject:** `[SECURITY] CloudCastle DI - Vulnerability Description`

#### 3. Telegram (for urgent cases)

- **Personal contact:** [@CloudCastle85](https://t.me/CloudCastle85)

---

## 📝 What to Include in Your Report

Please provide the following information:

1. **Vulnerability Description:**
   - Type of vulnerability (injection, DoS, memory leak, etc.)
   - Affected components
   - Potential impact

2. **Steps to Reproduce:**
   - Minimal reproducible code example
   - PHP and CloudCastle DI version
   - Environment configuration

3. **Possible Solution (if known):**
   - Fix suggestions
   - Patches or pull requests (private)

4. **Severity:**
   - Critical
   - High
   - Medium
   - Low

5. **Your contact information for follow-up**

---

## ⏱️ Response Process

### Our Response Timeline

| Stage | Time | Action |
|-------|------|--------|
| **Acknowledgment** | 24 hours | We confirm receipt of your report |
| **Initial Assessment** | 48 hours | Assess severity and reproduce the issue |
| **Fix Development** | 7 days | Create and test the fix |
| **Patch Release** | 14 days | Release security update |
| **Public Disclosure** | 30 days | Publish information after patch |

**Note:** Timeline may vary depending on vulnerability complexity.

### Coordinated Disclosure

We follow the **Responsible Disclosure** principle:

1. You report the vulnerability to us privately
2. We confirm and work on a fix
3. We release a patch
4. After 30 days (or by agreement) we publish details

---

## 🏆 Security Researcher Recognition

We value the contribution of security researchers and provide:

- ✅ **Public acknowledgment** in CHANGELOG and Security Advisory
- ✅ **Mention in CONTRIBUTORS.md**
- ✅ **Link to your profile** (GitHub, website, if desired)

---

## 🛡️ Current Security Measures

CloudCastle DI Container includes the following security measures:

### Protection Against Common Threats

- ✅ **Code Injection** — strict typing and validation
- ✅ **Memory Overflow** — efficient memory management
- ✅ **DoS Attacks** — optimized performance
- ✅ **Circular Dependencies** — automatic detection
- ✅ **Type Confusion** — type enforcement
- ✅ **Memory Leaks** — tested for 15M cycles
- ✅ **Deserialization** — safe handling

### Security Testing

- **15 automated security tests**
- **48 security assertions**
- **OWASP Top 10 compliance**
- **Regular checks** on every commit via CI/CD

**Full Report:** [reports/en/08_SECURITY.md](reports/en/08_SECURITY.md)

---

## 📚 Additional Resources

### Security Documentation

- [Security Report](reports/en/08_SECURITY.md) — detailed test results
- [Security Tests](tests/SecurityTest.php) — test source code
- [Best Practices](documentation/en/03_ADVANCED_FEATURES.md) — recommendations

### Running Security Tests

```bash
# Run all security tests
composer test:security

# Or directly
./vendor/bin/phpunit tests/SecurityTest.php --testdox
```

---

## 🔄 Security Update History

### v2.0.0 (October 16, 2025)

- ✅ Added 15 automated security tests
- ✅ Achieved A+ security rating
- ✅ Verified OWASP Top 10 compliance
- ✅ Tested protection against all major threats
- ✅ Zero critical vulnerabilities

---

## 📞 Contact

**For security questions:**

- **Email:** zorinalexey59292@gmail.com
- **GitHub Security:** https://github.com/zorinalexey/cloud-casstle-di-container/security
- **Telegram:** [@CloudCastle85](https://t.me/CloudCastle85)

**For general questions:**

- **Telegram channel:** [@cloud_castle_news](https://t.me/cloud_castle_news)
- **GitHub Issues:** https://github.com/zorinalexey/cloud-casstle-di-container/issues
- **VK:** https://vk.com/leha_zorin

---

## ⚖️ Disclosure Policy

We commit to:

- ✅ Respond quickly to security reports
- ✅ Keep you informed of the fix process
- ✅ Publicly acknowledge your contribution (if you agree)
- ✅ Release patches in a timely manner
- ✅ Coordinate public disclosure

We expect from researchers:

- ✅ Private vulnerability report
- ✅ Reasonable time for fixing
- ✅ Not exploit the vulnerability maliciously
- ✅ Not disclose information before patch release

---

## 🙏 Thank You

Thank you for helping keep CloudCastle DI Container secure!

Your efforts help make the project safer for all users.

---

**Last Updated:** October 16, 2025  
**Version:** 2.0.0

[Русский](SECURITY.md) | [Deutsch](SECURITY.de.md) | [Français](SECURITY.fr.md)

