# Аутентификация GitHub CLI через токен

Не приходит код? Используйте Personal Access Token!

---

## 🔑 Шаг 1: Создать токен

1. Откройте: https://github.com/settings/tokens/new

2. Заполните:
   - Note: `CloudCastle DI CLI`
   - Expiration: `90 days` (или больше)
   - Scopes: отметьте `repo` и `write:packages`

3. Нажмите **Generate token**

4. **СКОПИРУЙТЕ ТОКЕН** (показывается один раз!)

---

## 🔑 Шаг 2: Аутентифицироваться

Выполните команду (вставьте ваш токен):

```bash
echo 'ваш_токен_здесь' | gh auth login --with-token
```

**Пример:**
```bash
echo 'ghp_xxxxxxxxxxxxxxxxxxxx' | gh auth login --with-token
```

---

## 🚀 Шаг 3: Опубликовать

После успешной аутентификации запустите:

```bash
./publish-to-github.sh
```

Это автоматически:
✅ Создаст GitHub Release v2.0.0
✅ Обновит описание репозитория
✅ Добавит все topics

---

## ⚠️ Или ПРОЩЕ — через веб-интерфейс:

Не хотите возиться с токенами? Просто:

1. Откройте: https://github.com/zorinalexey/cloud-casstle-di-container/releases/new?tag=v2.0.0
2. Скопируйте текст из `.github/release-notes-v2.0.0.md`
3. Нажмите "Publish release"

⏱️ Время: 30 секунд!

