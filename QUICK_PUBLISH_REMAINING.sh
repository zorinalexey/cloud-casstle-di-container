#!/bin/bash

# Быстрая публикация на оставшихся платформах

echo "╔════════════════════════════════════════════════════════════╗"
echo "║    Публикация на оставшихся платформах                    ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# 1. VC.ru
echo "📝 1. VC.ru"
echo "   Открываю VC.ru и копирую текст..."
xdg-open "https://vc.ru/create" 2>/dev/null || open "https://vc.ru/create" 2>/dev/null || echo "   Откройте вручную: https://vc.ru/create"
cat marketing/posts/vcru_post.md | xclip -selection clipboard 2>/dev/null || cat marketing/posts/vcru_post.md | pbcopy 2>/dev/null || echo "   Скопируйте вручную: marketing/posts/vcru_post.md"
echo "   ✅ Текст скопирован в буфер обмена!"
echo ""
sleep 3

# 2. VK
echo "📝 2. VK"
echo "   Открываю VK и копирую текст..."
xdg-open "https://vk.com/" 2>/dev/null || open "https://vk.com/" 2>/dev/null || echo "   Откройте вручную: https://vk.com/"
cat marketing/posts/vk_post.md | xclip -selection clipboard 2>/dev/null || cat marketing/posts/vk_post.md | pbcopy 2>/dev/null || echo "   Скопируйте вручную: marketing/posts/vk_post.md"
echo "   ✅ Текст скопирован в буфер обмена!"
echo ""
sleep 3

# 3. Reddit
echo "📝 3. Reddit r/PHP"
echo "   Открываю Reddit и копирую текст..."
xdg-open "https://www.reddit.com/r/PHP/submit" 2>/dev/null || open "https://www.reddit.com/r/PHP/submit" 2>/dev/null || echo "   Откройте вручную: https://www.reddit.com/r/PHP/submit"
cat marketing/posts/reddit_php_v2_discussion.txt | xclip -selection clipboard 2>/dev/null || cat marketing/posts/reddit_php_v2_discussion.txt | pbcopy 2>/dev/null || echo "   Скопируйте вручную: marketing/posts/reddit_php_v2_discussion.txt"
echo "   ✅ Текст скопирован в буфер обмена!"
echo "   💡 Используйте flair: Discussion"
echo ""
sleep 3

# 4. Gitverse
echo "📝 4. Gitverse"
echo "   Открываю Gitverse..."
xdg-open "https://gitverse.ru/" 2>/dev/null || open "https://gitverse.ru/" 2>/dev/null || echo "   Откройте вручную: https://gitverse.ru/"
echo "   📖 Инструкция: marketing/posts/gitverse_readme.md"
echo ""
sleep 3

# 5. Awesome PHP
echo "📝 5. Awesome PHP (GitHub PR)"
echo "   Открываю Awesome PHP..."
xdg-open "https://github.com/ziadoz/awesome-php" 2>/dev/null || open "https://github.com/ziadoz/awesome-php" 2>/dev/null || echo "   Откройте вручную: https://github.com/ziadoz/awesome-php"
echo "   📖 Инструкция: marketing/posts/awesome_php_pr.md"
echo ""

echo "╔════════════════════════════════════════════════════════════╗"
echo "║              ✅ ВСЕ ПЛАТФОРМЫ ОТКРЫТЫ                     ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo "📋 Чек-лист:"
echo "   □ VC.ru — вставить текст и опубликовать"
echo "   □ VK — вставить текст и опубликовать"
echo "   □ Reddit — вставить текст, flair: Discussion"
echo "   □ Gitverse — создать репозиторий и push"
echo "   □ Awesome PHP — создать PR"
echo ""
echo "✨ Все тексты и инструкции готовы в marketing/posts/"
