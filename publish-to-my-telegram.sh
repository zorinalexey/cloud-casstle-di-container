#!/bin/bash

# Автоматическая публикация в свой Telegram канал

BOT_TOKEN="${TELEGRAM_BOT_TOKEN:-8498076528:AAEg-rzsuIyWX7xyhWSMAvYWhHNUKy-I-Oc}"
CHANNEL="${TELEGRAM_CHANNEL:-}"

if [ -z "$CHANNEL" ]; then
    echo "❌ Не задан TELEGRAM_CHANNEL"
    echo ""
    echo "💡 Использование:"
    echo "  export TELEGRAM_CHANNEL='@your_channel'"
    echo "  ./publish-to-my-telegram.sh"
    echo ""
    echo "📖 Инструкция: TELEGRAM_PUBLISH_GUIDE.md"
    exit 1
fi

# Читаем текст поста
POST_TEXT=$(cat marketing/posts/telegram_post.txt)

echo "╔════════════════════════════════════════════════════════════╗"
echo "║    Publishing to Telegram                                 ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo "📱 Публикация в канал: $CHANNEL"
echo ""

RESPONSE=$(curl -s -X POST "https://api.telegram.org/bot${BOT_TOKEN}/sendMessage" \
    -d "chat_id=${CHANNEL}" \
    --data-urlencode "text=${POST_TEXT}" \
    -d "disable_web_page_preview=false")

if echo "$RESPONSE" | grep -q '"ok":true'; then
    echo "✅ УСПЕШНО ОПУБЛИКОВАНО!"
    echo ""
    echo "🔗 Ссылка на канал: https://t.me/${CHANNEL#@}"
    echo ""
    echo "📊 Поделитесь ссылкой:"
    echo "  - В группах PHP разработчиков"
    echo "  - На Reddit, Habr, VK"
    echo "  - В комментариях к постам"
else
    echo "❌ Ошибка публикации"
    echo ""
    echo "Ответ API:"
    echo "$RESPONSE" | python3 -m json.tool 2>/dev/null || echo "$RESPONSE"
    echo ""
    echo "💡 Проверьте:"
    echo "  1. Бот добавлен как администратор канала?"
    echo "  2. У бота есть право 'Post Messages'?"
    echo "  3. Имя канала правильное? (начинается с @)"
fi

echo ""
echo "╔════════════════════════════════════════════════════════════╗"
echo "║              ПУБЛИКАЦИЯ ЗАВЕРШЕНА                          ║"
echo "╚════════════════════════════════════════════════════════════╝"
