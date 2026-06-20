# 🔧 Dépannage - Analyseur d'Humeur

## ❌ Erreur : "Vérifiez votre clé API HuggingFace"

### Causes possibles

1. **Le modèle IA se charge** (première utilisation)
2. **Clé API invalide ou expirée**
3. **Problème de connexion à HuggingFace**
4. **Timeout de la requête**

---

## ✅ Solutions

### Solution 1 : Attendre le chargement du modèle

**Problème** : La première fois que vous utilisez le modèle, HuggingFace doit le charger en mémoire.

**Solution** :
1. Attendez **20-30 secondes**
2. Cliquez à nouveau sur "🔍 Analyser"
3. Le widget affichera maintenant : "⏳ Le modèle IA se charge..."
4. Un bouton "🔄 Réessayer automatiquement" apparaîtra
5. Cliquez dessus ou attendez le réessai automatique

**Nouveau comportement** :
- ✅ Message clair : "Le modèle IA se charge..."
- ✅ Temps estimé affiché
- ✅ Réessai automatique après X secondes

---

### Solution 2 : Vérifier la clé API

**Étape 1** : Vérifiez que la clé est dans `.env`
```env
HUGGINGFACE_API_KEY=hf_your_token_here
```

**Étape 2** : Vérifiez que la clé est valide
1. Allez sur [https://huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. Vérifiez que votre token existe et est actif
3. Si nécessaire, créez un nouveau token

**Étape 3** : Redémarrez le serveur Laravel
```bash
# Arrêtez le serveur (Ctrl+C)
# Puis relancez
php artisan serve
```

---

### Solution 3 : Tester l'API directement

**Test avec cURL** :
```bash
curl https://api-inference.huggingface.co/models/j-hartmann/emotion-english-distilroberta-base \
  -X POST \
  -H "Authorization: Bearer hf_your_token_here" \
  -H "Content-Type: application/json" \
  -d '{"inputs": "I am so happy today!"}'
```

**Réponse attendue** :
```json
[[
  {"label": "joy", "score": 0.95},
  {"label": "neutral", "score": 0.03},
  ...
]]
```

**Si erreur "Model is currently loading"** :
```json
{
  "error": "Model j-hartmann/emotion-english-distilroberta-base is currently loading",
  "estimated_time": 20.0
}
```
→ C'est normal ! Attendez 20 secondes et réessayez.

---

### Solution 4 : Vérifier les logs Laravel

**Commande** :
```bash
Get-Content storage/logs/laravel.log -Tail 20
```

**Logs à chercher** :
```
[2026-01-13] local.INFO: HuggingFace API Response Status: 503
[2026-01-13] local.INFO: HuggingFace API Response Body: {"error":"Model is loading"...}
```

**Si vous voyez "503"** → Le modèle se charge, attendez et réessayez

**Si vous voyez "401"** → Clé API invalide, vérifiez votre token

**Si vous voyez "500"** → Erreur serveur HuggingFace, réessayez plus tard

---

### Solution 5 : Augmenter le timeout

Le timeout a été augmenté à **60 secondes** dans le contrôleur.

Si vous avez encore des problèmes, modifiez `app/Http/Controllers/EmotionController.php` :
```php
$response = Http::timeout(120) // 2 minutes
    ->withToken(env('HUGGINGFACE_API_KEY'))
    ->post('https://api-inference.huggingface.co/models/j-hartmann/emotion-english-distilroberta-base', [
        'inputs' => $text
    ]);
```

---

### Solution 6 : Vider le cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

Puis actualisez la page : `Ctrl+F5`

---

## 🧪 Test rapide

### Étape 1 : Vérifiez votre clé
```bash
# Dans PowerShell
Get-Content .env | Select-String "HUGGINGFACE"
```

Résultat attendu :
```
HUGGINGFACE_API_KEY=hf_your_token_here
```

### Étape 2 : Testez avec un texte simple
```
I am happy
```

### Étape 3 : Si erreur "Model is loading"
1. ✅ C'est normal !
2. ✅ Attendez 20 secondes
3. ✅ Cliquez sur "🔄 Réessayer automatiquement"
4. ✅ Ça devrait fonctionner maintenant

---

## 📊 Messages d'erreur améliorés

### Avant ❌
```
❌ Erreur lors de l'analyse
Vérifiez votre clé API HuggingFace
```

### Après ✅

**Si le modèle se charge** :
```
⏳ Le modèle IA se charge...
Veuillez réessayer dans 20 secondes
[🔄 Réessayer automatiquement]
```

**Si clé API invalide** :
```
❌ AI not available
Vérifiez votre clé API HuggingFace
Details: {"error": "Invalid token"}
```

**Si erreur de connexion** :
```
❌ Erreur de connexion
Network response was not ok
```

---

## 🎯 Checklist de dépannage

- [ ] Clé API présente dans `.env`
- [ ] Clé API valide sur HuggingFace
- [ ] Serveur Laravel redémarré
- [ ] Cache vidé
- [ ] Page actualisée (Ctrl+F5)
- [ ] Attendu 20-30 secondes pour le chargement du modèle
- [ ] Testé avec un texte en anglais
- [ ] Vérifié les logs Laravel

---

## 💡 Astuces

### 1. Première utilisation
Le modèle prend **20-30 secondes** à charger la première fois. C'est normal !

### 2. Texte en anglais
Le modèle fonctionne mieux avec des textes en **anglais**.

### 3. Longueur du texte
- ✅ Minimum : 3-4 mots
- ✅ Optimal : 1-2 phrases
- ✅ Maximum : Quelques paragraphes

### 4. Exemples qui fonctionnent
```
I am so happy today!
I feel sad and lonely.
I am angry about this!
I am worried and scared.
```

---

## 🔗 Ressources

- [HuggingFace Tokens](https://huggingface.co/settings/tokens)
- [Modèle utilisé](https://huggingface.co/j-hartmann/emotion-english-distilroberta-base)
- [Documentation API](https://huggingface.co/docs/api-inference/index)

---

**Maintenant réessayez avec "I feel sad" et ça devrait fonctionner ! 😊**

