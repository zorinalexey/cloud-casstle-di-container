#!/bin/bash

echo "🔄 Обновление отчетов на всех языках..."

# Ключевые метрики из свежих тестов
MAX_SERVICES="1,746,359"
EXTREME_OPS="499,667"
EXCEPTIONS_SEC="69,813"
REGISTER_SPEED="164,774"
GET_FIRST_SPEED="67,456"
GET_CACHED_SPEED="61,255"
HAS_SPEED="297,817"
LOAD_REG_SPEED="340,606"
LOAD_ACCESS_SPEED="271,834"
COMPILED_SPEED="506,274"

# Обновление английских отчетов
echo "📝 Updating English reports..."
sed -i 's/1,746,358/1,746,359/g' reports/en/*.md
sed -i 's/500,133/499,667/g' reports/en/*.md  
sed -i 's/69,032/69,813/g' reports/en/*.md
sed -i 's/168,492/164,774/g' reports/en/*.md
sed -i 's/66,935/67,456/g' reports/en/*.md
sed -i 's/61,145/61,255/g' reports/en/*.md
sed -i 's/304,132/297,817/g' reports/en/*.md

echo "   ✅ English reports updated"

# Обновление немецких отчетов
echo "📝 Updating German reports..."
sed -i 's/1\.746\.358/1.746.359/g' reports/de/*.md
sed -i 's/500\.133/499.667/g' reports/de/*.md
sed -i 's/69\.032/69.813/g' reports/de/*.md
sed -i 's/168\.492/164.774/g' reports/de/*.md

echo "   ✅ German reports updated"

# Обновление французских отчетов
echo "📝 Updating French reports..."
sed -i 's/1\.746\.358/1.746.359/g' reports/fr/*.md
sed -i 's/500\.133/499.667/g' reports/fr/*.md
sed -i 's/69\.032/69.813/g' reports/fr/*.md
sed -i 's/168\.492/164.774/g' reports/fr/*.md

echo "   ✅ French reports updated"

echo "✅ Все отчеты обновлены на всех языках!"
