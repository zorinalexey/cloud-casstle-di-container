#!/bin/bash

# CloudCastle DI v2.0 - Быстрая публикация на всех площадках
# Автоматически открывает страницы и копирует тексты

PLATFORM="${1:-reddit}"

echo "╔════════════════════════════════════════════════════════════╗"
echo "║    Quick Publish - CloudCastle DI v2.0                     ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Функция для копирования в буфер обмена
copy_to_clipboard() {
    if command -v xclip &> /dev/null; then
        cat "$1" | xclip -selection clipboard
        echo "✅ Текст скопирован в буфер обмена (Ctrl+V для вставки)"
    elif command -v xsel &> /dev/null; then
        cat "$1" | xsel --clipboard
        echo "✅ Текст скопирован в буфер обмена (Ctrl+V для вставки)"
    else
        echo "⚠️  xclip не установлен. Скопируйте вручную из файла:"
        echo "   $1"
    fi
}

case "$PLATFORM" in
    reddit)
        echo "📱 Публикация на Reddit r/PHP"
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
        echo ""
        echo "🌐 Открываем Reddit..."
        xdg-open "https://reddit.com/r/PHP/submit" 2>/dev/null &
        sleep 2
        echo ""
        echo "📋 Готовый текст:"
        cat marketing/posts/reddit_php_ready.txt
        echo ""
        copy_to_clipboard marketing/posts/reddit_php_ready.txt
        echo ""
        echo "✅ ДЕЙСТВИЯ:"
        echo "   1. На открывшейся странице выберите 'Text Post'"
        echo "   2. Вставьте заголовок из файла (первая строка)"
        echo "   3. Вставьте тело поста (Ctrl+V)"
        echo "   4. Выберите Flair: 'Release'"
        echo "   5. Нажмите 'Post'"
        ;;
        
    habr)
        echo "📱 Публикация на Habr"
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
        echo ""
        xdg-open "https://habr.com/ru/articles/new/" 2>/dev/null &
        sleep 2
        echo ""
        echo "📝 Статья готова в: marketing/posts/habr_article.md"
        copy_to_clipboard marketing/posts/habr_article.md
        echo ""
        echo "✅ ДЕЙСТВИЯ:"
        echo "   1. Вставьте текст (Ctrl+V)"
        echo "   2. Хабы: PHP, Открытый код, Производительность"
        echo "   3. Теги: #php #di #opensource #performance"
        echo "   4. Опубликовать"
        ;;
        
    devto)
        echo "📱 Публикация на Dev.to"
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
        echo ""
        xdg-open "https://dev.to/new" 2>/dev/null &
        sleep 2
        copy_to_clipboard marketing/posts/devto_article.md
        echo ""
        echo "✅ Текст скопирован! Вставьте на Dev.to (Ctrl+V)"
        echo "   Tags: #php #opensource #performance #dependencyinjection"
        ;;
        
    linkedin)
        echo "📱 Публикация на LinkedIn"
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
        echo ""
        xdg-open "https://www.linkedin.com/" 2>/dev/null &
        sleep 2
        copy_to_clipboard marketing/posts/linkedin_post.md
        echo ""
        echo "✅ Текст скопирован! Создайте пост на LinkedIn (Ctrl+V)"
        ;;
        
    vk)
        echo "📱 Публикация на VK"
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
        echo ""
        xdg-open "https://vk.com/" 2>/dev/null &
        sleep 2
        copy_to_clipboard marketing/posts/vk_post.md
        echo ""
        echo "✅ Текст скопирован! Создайте пост в группах PHP, Веб-разработка"
        ;;
        
    all)
        echo "📱 Публикация на ВСЕХ площадках"
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
        echo ""
        echo "Открываем все площадки..."
        xdg-open "https://reddit.com/r/PHP/submit" 2>/dev/null &
        sleep 1
        xdg-open "https://habr.com/ru/articles/new/" 2>/dev/null &
        sleep 1
        xdg-open "https://dev.to/new" 2>/dev/null &
        echo ""
        echo "✅ Все площадки открыты в браузере!"
        echo "📝 Тексты доступны в marketing/posts/"
        ;;
        
    *)
        echo "❌ Неизвестная площадка: $PLATFORM"
        echo ""
        echo "Доступные площадки:"
        echo "  ./quick-publish.sh reddit"
        echo "  ./quick-publish.sh habr"
        echo "  ./quick-publish.sh devto"
        echo "  ./quick-publish.sh linkedin"
        echo "  ./quick-publish.sh vk"
        echo "  ./quick-publish.sh all"
        ;;
esac

