# 🛠️ Contributing

Merci de votre intérêt à contribuer à CloudCastle DI Container !

Nous accueillons les contributions de la communauté et valorisons chaque suggestion d'amélioration du projet.

---

## 📋 Comment Contribuer

### 🐛 Rapports de Bugs

1. **Vérifier les issues existantes** — le problème pourrait déjà être connu
2. **Créer un nouveau issue** avec description détaillée :
   - Version PHP et CloudCastle DI
   - Étapes de reproduction
   - Comportement attendu
   - Comportement actuel
   - Logs d'erreur (si applicable)

### ✨ Demandes de Fonctionnalités

1. **Discuter l'idée** dans un issue tagué `enhancement`
2. **Décrire le problème** que la fonctionnalité résout
3. **Proposer une solution** avec exemples d'usage
4. **Attendre les retours** des mainteneurs

### 🔧 Pull Requests

1. **Fork le repository**
2. **Créer une branche feature** : `git checkout -b feature/amazing-feature`
3. **Suivre les standards de code** (voir ci-dessous)
4. **Ajouter des tests** pour la nouvelle fonctionnalité
5. **Mettre à jour la documentation** si nécessaire
6. **Créer un Pull Request**

---

## 📏 Standards de Code

### 🎯 Style de Code PHP

Nous suivons le standard **PSR-12** :

```bash
# Vérifier le style de code
composer phpcs

# Auto-correction
composer phpcs:fix
```

### 🧪 Tests

**Toujours ajouter des tests pour la nouvelle fonctionnalité :**

```bash
# Exécuter tous les tests
composer test

# Exécuter avec couverture
composer test:coverage

# Exécuter les tests de sécurité
composer test:security
```

### 📊 Performance

**Les nouvelles fonctionnalités doivent passer les benchmarks :**

```bash
# Exécuter les benchmarks
composer benchmark

# Tests de charge
composer load-test

# Tests de stress
composer stress-test
```

---

## 🔍 Processus de Review

### ✅ Ce que nous vérifions

1. **Exactitude du code** — la logique fonctionne correctement
2. **Conformité aux standards** — PSR-12, typage
3. **Couverture de tests** — nouvelles fonctionnalités testées
4. **Performance** — pas de régressions
5. **Documentation** — mise à jour si nécessaire
6. **Sécurité** — pas de vulnérabilités

### ⏱️ Temps de Review

- **Changements simples :** 1-3 jours
- **Changements complexes :** 3-7 jours
- **Changements critiques :** 1-2 semaines

---

## 🏗️ Développement

### 🚀 Configuration de l'environnement

```bash
# Cloner le repository
git clone https://github.com/zorinalexey/cloud-casstle-di-container.git
cd cloud-casstle-di-container

# Installer les dépendances
composer install

# Exécuter les tests
composer test
```

### 📁 Structure du projet

```
src/                    # Code source
├── Attribute/          # Attributs PHP 8.4
├── Container.php       # Container principal
├── CompiledContainer.php
└── ...

tests/                  # Tests
├── Unit/              # Tests unitaires
├── SecurityTest.php   # Tests de sécurité
├── LoadTest.php       # Tests de charge
└── ...

documentation/          # Documentation
├── ru/                # Documentation russe
├── en/                # Documentation anglaise
├── de/                # Documentation allemande
└── fr/                # Documentation française

reports/               # Rapports de tests
├── ru/                # Rapports russes
├── en/                # Rapports anglais
├── de/                # Rapports allemands
└── fr/                # Rapports français
```

### 🔧 Commandes utiles

```bash
# Analyse statique
composer analyse

# Corriger le style de code
composer php-cs-fixer:fix

# Métriques de qualité
composer metrics

# Compiler le container
composer compile
```

---

## 📚 Documentation

### 📝 Mises à jour de documentation

Lors de l'ajout de nouvelles fonctionnalités, **toujours mettre à jour :**

1. **README.md** — documentation principale
2. **Documentation** dans `documentation/` (toutes les langues)
3. **Exemples** dans `examples/`
4. **Documentation API** dans les fichiers pertinents

### 🌍 Support multilingue

La documentation est maintenue en 4 langues :
- **Russe** (primaire)
- **Anglais**
- **Allemand**
- **Français**

**Lors de l'ajout de nouvelle documentation, créer des fichiers dans toutes les langues.**

---

## 🏆 Reconnaissance des contributions

### ✅ Ce que nous faisons

- **Reconnaissance publique** dans CHANGELOG.md
- **Mention dans CONTRIBUTORS.md**
- **Lien vers votre profil** (GitHub, site web)
- **Badges spéciaux** pour les contributeurs actifs

### 🎯 Types de contributions

- **🐛 Rapports de bugs** — signalements de bugs
- **✨ Demandes de fonctionnalités** — suggestions de fonctionnalités
- **🔧 Contributions de code** — code
- **📚 Documentation** — documentation
- **🧪 Tests** — tests
- **🎨 Design** — design/UI
- **🌍 Traduction** — traductions
- **📢 Promotion** — promotion du projet

---

## 📞 Communication

### 💬 Discussion

- **GitHub Issues** — pour discuter des problèmes et fonctionnalités
- **Canal Telegram** — [@cloud_castle_news](https://t.me/cloud_castle_news)
- **Email** — zorinalexey59292@gmail.com

### 🆘 Aide

Si vous avez des questions sur le processus de développement :

1. **Vérifier la documentation** dans `documentation/`
2. **Rechercher dans les issues** — la question pourrait avoir été discutée
3. **Créer un nouveau issue** tagué `question`
4. **Écrire dans Telegram** — [@CloudCastle85](https://t.me/CloudCastle85)

---

## ⚖️ Code de Conduite

### 🤝 Nos principes

- **Respect** — se traiter mutuellement avec respect
- **Tolérance** — accepter différentes opinions et expériences
- **Constructivité** — se concentrer sur la résolution de problèmes
- **Ouverture** — accueillir de nouvelles idées

### 🚫 Comportement inacceptable

- Insultes ou discrimination
- Spam ou flood
- Violations de la vie privée
- Tout comportement créant une atmosphère inconfortable

---

## 📋 Checkliste PR

Avant de soumettre un Pull Request, s'assurer :

- [ ] Le code suit PSR-12
- [ ] Tests ajoutés pour la nouvelle fonctionnalité
- [ ] Tous les tests passent (`composer test`)
- [ ] Documentation mise à jour
- [ ] Pas de régressions de performance
- [ ] Code passé l'analyse statique
- [ ] PR a une description claire

---

## 🙏 Merci

Merci de contribuer à CloudCastle DI Container !

Chaque amélioration rend le projet meilleur pour toute la communauté.

---

**Dernière mise à jour :** 16 octobre 2025

[Русский](CONTRIBUTING.md) | [English](CONTRIBUTING.en.md) | [Deutsch](CONTRIBUTING.de.md)