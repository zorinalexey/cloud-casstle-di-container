# 🔒 Politique de sécurité de CloudCastle DI Container

---

## 📋 Versions prises en charge

Nous fournissons actuellement des mises à jour de sécurité pour les versions suivantes :

| Version | Support de sécurité |
|---------|---------------------|
| 2.0.x   | ✅ Support complet |
| 1.x.x   | ⚠️ Vulnérabilités critiques uniquement |
| < 1.0   | ❌ Non pris en charge |

**Recommandation :** Utilisez la dernière version 2.0.x pour recevoir toutes les mises à jour de sécurité.

---

## 🔐 Évaluation de la sécurité

**CloudCastle DI Container v2.0.0 :**

- **Évaluation de sécurité :** A+ ⭐⭐⭐⭐⭐
- **Tests de sécurité :** 15/15 (100%)
- **Vulnérabilités critiques :** 0
- **OWASP Top 10 :** Conforme
- **Dernière révision :** 16 octobre 2025

**Rapport détaillé :** Voir [reports/fr/08_SECURITY.md](reports/fr/08_SECURITY.md)

---

## 🐛 Signalement des vulnérabilités

Nous prenons la sécurité de CloudCastle DI Container au sérieux et apprécions les efforts de la communauté pour découvrir et divulguer de manière responsable les vulnérabilités.

### Comment signaler une vulnérabilité

**NE créez PAS d'issues publiques pour les vulnérabilités de sécurité !**

Au lieu de cela, veuillez les signaler de manière privée en utilisant l'une des méthodes suivantes :

#### 1. GitHub Security Advisory (recommandé)

1. Allez sur https://github.com/zorinalexey/cloud-casstle-di-container/security/advisories
2. Cliquez sur **"Report a vulnerability"**
3. Remplissez le formulaire avec une description détaillée

#### 2. E-mail

Envoyez une description détaillée de la vulnérabilité à :
- **Principal :** zorinalexey59292@gmail.com
- **Alternatif :** alex-4-17@yandex.ru

**Objet :** `[SECURITY] CloudCastle DI - Description de la vulnérabilité`

#### 3. Telegram (pour les cas urgents)

- **Contact personnel :** [@CloudCastle85](https://t.me/CloudCastle85)

---

## 📝 Que inclure dans votre rapport

Veuillez fournir les informations suivantes :

1. **Description de la vulnérabilité :**
   - Type de vulnérabilité (injection, DoS, fuite mémoire, etc.)
   - Composants affectés
   - Impact potentiel

2. **Étapes de reproduction :**
   - Exemple de code minimal reproductible
   - Version PHP et CloudCastle DI
   - Configuration de l'environnement

3. **Solution possible (si connue) :**
   - Suggestions de correction
   - Patches ou pull requests (privés)

4. **Gravité :**
   - Critique
   - Élevée
   - Moyenne
   - Faible

5. **Vos coordonnées pour le suivi**

---

## ⏱️ Processus de réponse

### Notre calendrier de réponse

| Étape | Délai | Action |
|-------|-------|--------|
| **Accusé de réception** | 24 heures | Nous confirmons la réception de votre rapport |
| **Évaluation initiale** | 48 heures | Évaluer la gravité et reproduire le problème |
| **Développement du correctif** | 7 jours | Créer et tester la correction |
| **Publication du patch** | 14 jours | Publier la mise à jour de sécurité |
| **Divulgation publique** | 30 jours | Publier les informations après le patch |

**Note :** Le calendrier peut varier selon la complexité de la vulnérabilité.

### Divulgation coordonnée

Nous suivons le principe de la **Divulgation responsable** :

1. Vous nous signalez la vulnérabilité en privé
2. Nous confirmons et travaillons sur une correction
3. Nous publions un patch
4. Après 30 jours (ou par accord) nous publions les détails

---

## 🏆 Reconnaissance des chercheurs en sécurité

Nous valorisons la contribution des chercheurs en sécurité et offrons :

- ✅ **Remerciement public** dans CHANGELOG et Security Advisory
- ✅ **Mention dans CONTRIBUTORS.md**
- ✅ **Lien vers votre profil** (GitHub, site web, si souhaité)

---

## 🛡️ Mesures de sécurité actuelles

CloudCastle DI Container comprend les mesures de sécurité suivantes :

### Protection contre les menaces courantes

- ✅ **Code Injection** — typage strict et validation
- ✅ **Memory Overflow** — gestion efficace de la mémoire
- ✅ **DoS Attacks** — performance optimisée
- ✅ **Circular Dependencies** — détection automatique
- ✅ **Type Confusion** — application des types
- ✅ **Memory Leaks** — testé pour 15M cycles
- ✅ **Deserialization** — gestion sécurisée

### Tests de sécurité

- **15 tests de sécurité automatisés**
- **48 assertions de sécurité**
- **Conformité OWASP Top 10**
- **Vérifications régulières** à chaque commit via CI/CD

**Rapport complet :** [reports/fr/08_SECURITY.md](reports/fr/08_SECURITY.md)

---

## 📞 Contact

**Pour les questions de sécurité :**

- **E-mail :** zorinalexey59292@gmail.com
- **GitHub Security :** https://github.com/zorinalexey/cloud-casstle-di-container/security
- **Telegram :** [@CloudCastle85](https://t.me/CloudCastle85)

**Pour les questions générales :**

- **Canal Telegram :** [@cloud_castle_news](https://t.me/cloud_castle_news)
- **GitHub Issues :** https://github.com/zorinalexey/cloud-casstle-di-container/issues
- **VK :** https://vk.com/leha_zorin

---

## 🙏 Merci

Merci d'aider à maintenir CloudCastle DI Container sécurisé !

Vos efforts contribuent à rendre le projet plus sûr pour tous les utilisateurs.

---

**Dernière mise à jour :** 16 octobre 2025  
**Version :** 2.0.0

[Русский](../../SECURITY.md) | [English](../en/SECURITY.md) | [Deutsch](../de/SECURITY.md)

