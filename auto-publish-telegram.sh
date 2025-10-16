#!/bin/bash

# Автоматическая публикация в Telegram через Bot API

BOT_TOKEN="${TELEGRAM_BOT_TOKEN:-}"
CHANNELS=("@php_legion" "@web_dev_chat" "@ru_php")

if [ -z "$BOT_TOKEN" ]; then
    echo "❌ Не задан TELEGRAM_BOT_TOKEN"
    echo ""
    echo "🔑 Как получить токен:"
    echo "  1. Написать @BotFather в Telegram"
    echo "  2. Отправить: /newbot"
    echo "  3. Следовать инструкциям"
    echo "  4. Скопировать токен"
    echo ""
    echo "💡 Использование:"
    echo "  export TELEGRAM_BOT_TOKEN='ваш_токен'"
    echo "  ./auto-publish-telegram.sh"
    echo ""
    exit 1
fi

# Текст поста (короткая версия для Telegram)
POST_TEXT="🚀 *CloudCastle DI Container v2\.0* — самый быстрый DI контейнер для PHP\!

🏆 *Рекордные показатели:*
✅ 500,133 операций/сек
✅ В 3\-4 раза быстрее Symfony и Laravel  
✅ 0\.001 МБ утечек за 15M циклов
✅ 98\.4% тестов пройдено

🎨 *Возможности:*
• PHP 8\+ Attributes
• Autowiring
• Lazy Loading
• Compiled Container
• Scoped Containers

🔗 GitHub: https://github\.com/zorinalexey/cloud\-casstle\-di\-container

⭐ Буду рад звезде на GitHub\!"

echo "╔════════════════════════════════════════════════════════════╗"
echo "║    Publishing to Telegram Channels                        ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

for CHANNEL in "${CHANNELS[@]}"; do
    echo "📱 Публикация в $CHANNEL..."
    
    RESPONSE=$(curl -s -X POST "https://api.telegram.org/bot${BOT_TOKEN}/sendMessage" \
        -d "chat_id=${CHANNEL}" \
        -d "text=${POST_TEXT}" \
        -d "parse_mode=MarkdownV2" \
        -d "disable_web_page_preview=false")
    
    if echo "$RESPONSE" | grep -q '"ok":true'; then
        echo "✅ Опубликовано в $CHANNEL"
    else
        echo "❌ Ошибка публикации в $CHANNEL"
        echo "   Возможно, бот не добавлен в канал или нет прав"
    fi
    echo ""
    sleep 2
done

echo "╔════════════════════════════════════════════════════════════╗"
echo "║              ✅ ПУБЛИКАЦИЯ ЗАВЕРШЕНА                       ║"
echo "╚════════════════════════════════════════════════════════════╝"
