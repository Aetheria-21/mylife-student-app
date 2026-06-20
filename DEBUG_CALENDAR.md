# Debug - Erreur 400 Bad Request

## 🔍 Problème identifié

L'erreur "Bad Request" (400) vient probablement de:
1. La validation Laravel qui rejette les données
2. Le format des dates envoyées
3. Un problème avec le token CSRF

## ✅ Corrections appliquées

### 1. CalendarEventController.php
- ✅ Suppression de la validation stricte
- ✅ Ajout de logs pour déboguer
- ✅ Vérification manuelle des champs requis
- ✅ Meilleure gestion des erreurs

### 2. Comment déboguer

#### Étape 1: Vérifier les logs Laravel
```bash
# Ouvrez le fichier de logs
tail -f storage/logs/laravel.log

# Ou sur Windows
Get-Content storage/logs/laravel.log -Tail 50 -Wait
```

#### Étape 2: Vérifier la console du navigateur
1. Ouvrez la console (F12)
2. Allez dans l'onglet "Network"
3. Cliquez sur une date du calendrier
4. Regardez la requête POST vers `/calendar/events`
5. Vérifiez:
   - **Request Headers**: Le token CSRF est-il présent?
   - **Request Payload**: Les données sont-elles correctes?
   - **Response**: Quel est le message d'erreur exact?

#### Étape 3: Tester manuellement avec curl
```bash
curl -X POST http://127.0.0.1:8000/calendar/events \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: VOTRE_TOKEN" \
  -d '{
    "title": "Test Event",
    "start": "2026-01-05T09:00:00",
    "end": "2026-01-05T10:00:00"
  }'
```

## 🐛 Causes possibles

### Cause 1: Token CSRF invalide
**Solution**: Vérifiez que le token CSRF est bien généré
```blade
{{ csrf_token() }}
```

### Cause 2: Format de date incorrect
**Solution**: Le format doit être `YYYY-MM-DDTHH:MM:SS`
```javascript
// ✅ Correct
start: "2026-01-05T09:00:00"

// ❌ Incorrect
start: "2026-01-05"
start: "05/01/2026"
```

### Cause 3: Middleware CSRF
**Solution**: Vérifiez que la route n'est pas bloquée par le middleware CSRF

### Cause 4: Token Google expiré
**Solution**: Reconnectez-vous avec Google

## 🧪 Test rapide

Essayez ce code dans la console du navigateur:
```javascript
fetch('/calendar/events', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
  },
  body: JSON.stringify({
    title: 'Test Event',
    start: '2026-01-05T09:00:00',
    end: '2026-01-05T10:00:00'
  })
})
.then(res => res.json())
.then(data => console.log('Success:', data))
.catch(err => console.error('Error:', err));
```

## 📝 Prochaines étapes

1. ✅ Réessayez de créer un événement
2. 📋 Vérifiez les logs Laravel (`storage/logs/laravel.log`)
3. 🔍 Regardez la console du navigateur
4. 📧 Partagez le message d'erreur exact si le problème persiste

## 💡 Astuce

Ajoutez une balise meta pour le CSRF token dans votre layout:
```blade
<meta name="csrf-token" content="{{ csrf_token() }}">
```

Puis utilisez-la dans votre JavaScript:
```javascript
'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
```

