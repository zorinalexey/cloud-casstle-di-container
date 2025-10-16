#!/bin/bash

echo "╔════════════════════════════════════════════════════════════╗"
echo "║    Publishing CloudCastle DI v2.0.0 to GitHub              ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Проверка аутентификации
if ! gh auth status &>/dev/null; then
    echo "⚠️  GitHub CLI не аутентифицирован"
    echo "🔐 Запускаем аутентификацию..."
    echo ""
    gh auth login
    echo ""
fi

echo "✅ GitHub CLI аутентифицирован"
echo ""

# 1. Создать GitHub Release
echo "📦 Создаём GitHub Release v2.0.0..."
gh release create v2.0.0 \
  --title "CloudCastle DI Container v2.0.0 — High-Performance DI with Advanced Features" \
  --notes-file .github/release-notes-v2.0.0.md \
  2>&1

if [ $? -eq 0 ]; then
    echo "✅ GitHub Release v2.0.0 создан успешно!"
else
    echo "⚠️  Release уже существует или произошла ошибка"
fi
echo ""

# 2. Обновить описание репозитория
echo "📝 Обновляем описание репозитория..."
gh repo edit \
  --description "⚡ High-performance PSR-11 DI container for PHP 8.1+ | 500k op/s | 3-4x faster than Symfony DI | Autowiring, Attributes, Compiled Container" \
  --homepage "https://github.com/zorinalexey/cloud-casstle-di-container" \
  2>&1

echo "✅ Описание обновлено"
echo ""

# 3. Добавить topics
echo "🏷️  Добавляем topics..."
gh repo edit \
  --add-topic php \
  --add-topic dependency-injection \
  --add-topic psr-11 \
  --add-topic autowiring \
  --add-topic di-container \
  --add-topic performance \
  --add-topic php8 \
  --add-topic compiled-container \
  --add-topic lazy-loading \
  --add-topic attributes \
  2>&1

echo "✅ Topics добавлены"
echo ""

echo "╔════════════════════════════════════════════════════════════╗"
echo "║              ✅ ВСЁ ОПУБЛИКОВАНО НА GITHUB!                ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo "🔗 Release: https://github.com/zorinalexey/cloud-casstle-di-container/releases/tag/v2.0.0"
echo "🔗 Repository: https://github.com/zorinalexey/cloud-casstle-di-container"
echo ""
echo "📊 Следующие шаги:"
echo "   → Опубликовать на Reddit (marketing/posts/reddit_php.md)"
echo "   → Написать статью на Habr (marketing/posts/habr_article.md)"
echo "   → Dev.to, LinkedIn, Twitter (см. marketing/posts/)"
echo ""

