#!/bin/bash

# CloudCastle DI v2.0.0 - GitHub Release Creator
# Этот скрипт создаст Release на GitHub

REPO="zorinalexey/cloud-casstle-di-container"
TAG="v2.0.0"

echo "╔════════════════════════════════════════════════════════════╗"
echo "║    Creating GitHub Release v2.0.0                          ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Проверка что тег существует
if ! git tag | grep -q "^${TAG}$"; then
    echo "❌ Tag ${TAG} не найден локально!"
    exit 1
fi

echo "✅ Tag ${TAG} существует локально"

# Проверка что тег запушен
if ! git ls-remote --tags origin | grep -q "refs/tags/${TAG}"; then
    echo "❌ Tag ${TAG} не запушен в origin!"
    echo "Запушим тег..."
    git push origin ${TAG}
fi

echo "✅ Tag ${TAG} запушен в origin"
echo ""

# Release notes
RELEASE_BODY=$(cat .github/release-notes-v2.0.0.md)

echo "📝 Release будет создан с:"
echo "   Repository: ${REPO}"
echo "   Tag: ${TAG}"
echo "   Title: CloudCastle DI Container v2.0.0"
echo ""

# Инструкция для создания вручную
echo "🌐 Для создания Release:"
echo ""
echo "1. Перейдите на:"
echo "   https://github.com/${REPO}/releases/new?tag=${TAG}"
echo ""
echo "2. Заполните:"
echo "   Title: CloudCastle DI Container v2.0.0 — High-Performance DI with Advanced Features"
echo "   Description: скопируйте из .github/release-notes-v2.0.0.md"
echo ""
echo "3. Нажмите 'Publish release'"
echo ""
echo "✅ Release notes готовы в: .github/release-notes-v2.0.0.md"
echo ""
echo "📊 Или используйте GitHub CLI (если установлен):"
echo "   gh release create ${TAG} --title 'CloudCastle DI Container v2.0.0' --notes-file .github/release-notes-v2.0.0.md"
echo ""

