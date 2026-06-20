# Guide de Test - Calendrier

## ✅ Corrections effectuées

### 1. **CalendarEventController.php**
- ✅ Ajout des imports manquants (`DateTime`, `Event`)
- ✅ Ajout de la validation des données
- ✅ Gestion des erreurs avec try/catch
- ✅ Réponse JSON avec `success: true/false`
- ✅ Conversion correcte des dates au format RFC3339
- ✅ Durée par défaut de 1 heure si pas de date de fin

### 2. **home.blade.php**
- ✅ Correction de la syntaxe JavaScript
- ✅ Amélioration du prompt avec emoji
- ✅ Gestion des erreurs côté client
- ✅ Messages de confirmation
- ✅ Vérification de `data.success` avant d'ajouter l'événement

## 🧪 Comment tester

### Test 1: Créer un événement
1. Ouvrez votre navigateur et allez sur `/home`
2. Cliquez sur n'importe quelle date du calendrier
3. Entrez un titre dans le prompt (ex: "Réunion")
4. Cliquez OK
5. ✅ Vous devriez voir:
   - Un message "✅ Événement créé avec succès!"
   - L'événement apparaît sur le calendrier
   - L'événement dure 1 heure (9h-10h par défaut)

### Test 2: Modifier un événement
1. Cliquez sur un événement existant
2. Choisissez "OK" pour modifier
3. Modifiez le titre, la description ou les dates
4. Cliquez "Enregistrer"
5. ✅ L'événement est mis à jour

### Test 3: Supprimer un événement
1. Cliquez sur un événement
2. Choisissez "Annuler" pour supprimer
3. Confirmez la suppression
4. ✅ L'événement disparaît

### Test 4: Déplacer un événement
1. Glissez-déposez un événement vers une autre date
2. ✅ L'événement est automatiquement mis à jour

## 🐛 Débogage

### Si l'événement ne se crée pas:

1. **Ouvrez la console du navigateur** (F12)
2. Regardez les erreurs JavaScript
3. Vérifiez la réponse du serveur dans l'onglet "Network"

### Si vous voyez une erreur 500:

1. Vérifiez les logs Laravel: `storage/logs/laravel.log`
2. Vérifiez que votre token Google est valide
3. Vérifiez que les variables d'environnement sont correctes:
   ```
   GOOGLE_CLIENT_ID=...
   GOOGLE_CLIENT_SECRET=...
   GOOGLE_REDIRECT_URI=...
   ```

### Si le token Google est expiré:

1. Déconnectez-vous
2. Reconnectez-vous avec Google
3. Réessayez

## 📝 Améliorations possibles

Si vous voulez améliorer l'ajout d'événements, vous pouvez:

### Option A: Ajouter un champ pour l'heure
```javascript
let title = prompt('📝 Titre de l\'événement:');
if (title) {
    let time = prompt('🕐 Heure (ex: 14:30):', '09:00');
    // Utiliser cette heure au lieu de 09:00 par défaut
}
```

### Option B: Utiliser le modal complet
Le modal est déjà présent dans votre code ! Pour l'activer:
1. Remplacez le `prompt` par `openModal()`
2. Vous aurez un formulaire complet avec description et dates personnalisables

### Option C: Ajouter des catégories/couleurs
```javascript
let category = prompt('🎨 Catégorie (travail/personnel/autre):');
// Ajouter une couleur selon la catégorie
```

## 🎯 Prochaines étapes

1. ✅ Tester la création d'événements
2. ✅ Tester la modification
3. ✅ Tester la suppression
4. ⏭️ Décider si vous voulez garder le prompt ou utiliser le modal
5. ⏭️ Ajouter des fonctionnalités supplémentaires (couleurs, rappels, etc.)

## 💡 Astuce

Pour basculer entre le prompt et le modal, vous pouvez ajouter un bouton dans l'interface:
- Clic simple = prompt rapide
- Double-clic = modal détaillé

