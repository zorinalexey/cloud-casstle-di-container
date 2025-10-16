#!/bin/bash

echo "╔════════════════════════════════════════════════════════════╗"
echo "║    Auto-publishing to Reddit r/PHP                         ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Reddit требует OAuth аутентификацию
# Для автоматической публикации нужен Reddit API token

echo "📝 Подготовка поста для Reddit r/PHP..."
echo ""

# Извлечь title и body из готового поста
TITLE=$(grep -A1 "^**Title:**" marketing/posts/reddit_php.md | tail -1)
BODY_FILE="marketing/posts/reddit_php.md"

echo "📋 Title: $TITLE"
echo ""

# Reddit API endpoint
REDDIT_API="https://oauth.reddit.com/api/submit"

echo "⚠️  Для автоматической публикации на Reddit нужен API token"
echo ""
echo "🔑 Получить токен:"
echo "   1. Перейти: https://www.reddit.com/prefs/apps"
echo "   2. Создать app (script type)"
echo "   3. Получить client_id и client_secret"
echo ""
echo "📝 Или опубликуйте вручную (ПРОЩЕ):"
echo "   1. Откройте: https://reddit.com/r/PHP/submit"
echo "   2. Скопируйте текст из: marketing/posts/reddit_php.md"
echo "   3. Нажмите Post"
echo ""
echo "⏱️  Время: 2 минуты"
echo ""

# Открыть Reddit в браузере
if command -v xdg-open &> /dev/null; then
    echo "🌐 Открываем Reddit в браузере..."
    xdg-open "https://reddit.com/r/PHP/submit" 2>/dev/null &
    echo "✅ Браузер открыт!"
    echo ""
    echo "📋 Скопируйте текст из marketing/posts/reddit_php.md и вставьте"
elif command -v open &> /dev/null; then
    open "https://reddit.com/r/PHP/submit"
else
    echo "Откройте вручную: https://reddit.com/r/PHP/submit"
fi

