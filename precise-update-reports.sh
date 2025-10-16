#!/bin/bash

# Точное обновление всех отчетов на всех языках

echo "🔄 Precise update of all reports..."

# English
echo "📝 Updating English reports..."
sed -i 's/| \*\*TOTAL\*\* | \*\*64\*\* | \*\*63\*\* /| **TOTAL** | **83** | **82** /g' reports/en/01_SUMMARY.md 2>/dev/null
sed -i 's/98\.4%/98.8%/g' reports/en/01_SUMMARY.md 2>/dev/null

# German  
echo "📝 Updating German reports..."
sed -i 's/| \*\*GESAMT\*\* | \*\*64\*\* | \*\*63\*\* /| **GESAMT** | **83** | **82** /g' reports/de/01_SUMMARY.md 2>/dev/null
sed -i 's/98,4%/98,8%/g' reports/de/01_SUMMARY.md 2>/dev/null

# French
echo "📝 Updating French reports..."
sed -i 's/| \*\*TOTAL\*\* | \*\*64\*\* | \*\*63\*\* /| **TOTAL** | **83** | **82** /g' reports/fr/01_SUMMARY.md 2>/dev/null
sed -i 's/98,4%/98,8%/g' reports/fr/01_SUMMARY.md 2>/dev/null

echo "✅ Done!"
