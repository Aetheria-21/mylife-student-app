# 🕌 Guide du Widget Horaires de Prière

## ✅ Améliorations effectuées

### Avant ❌
```html
<select id="city">
  <option value="Tunis,Tunisia">Tunis</option>
  <option value="Paris,France">Paris</option>
  <option value="Cairo,Egypt">Cairo</option>
</select>
<button onclick="loadPrayer()">Get Prayer Times</button>
<div id="prayerResult"></div>
```
- ❌ Pas de style
- ❌ Design basique
- ❌ Pas de gestion d'erreurs
- ❌ Pas de loading state
- ❌ Seulement 3 villes

### Après ✅
- ✅ **Widget moderne** avec dégradé violet-rose
- ✅ **Design responsive** (mobile + desktop)
- ✅ **8 villes disponibles** (Tunis, Paris, Cairo, Mecca, Medina, Istanbul, Dubai, London)
- ✅ **Affichage complet** : 5 prières + Sunrise, Midnight, Imsak
- ✅ **Cartes colorées** pour chaque prière avec emojis
- ✅ **Date hijri** affichée
- ✅ **Gestion des états** : Loading, Error, Default, Success
- ✅ **Highlight automatique** de la prière actuelle
- ✅ **Animations** et effets hover

## 🎨 Design du Widget

### Couleurs par prière
| Prière | Emoji | Couleur | Signification |
|--------|-------|---------|---------------|
| **Fajr** | 🌅 | Bleu | Aube |
| **Dhuhr** | ☀️ | Jaune | Midi |
| **Asr** | 🌤️ | Orange | Après-midi |
| **Maghrib** | 🌆 | Rose | Coucher du soleil |
| **Isha** | 🌙 | Violet | Nuit |

### Structure
```
┌─────────────────────────────────────────────────────────┐
│  🕌 Horaires de Prière    [Ville ▼] [🕌 Charger]       │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │           📍 Tunis, Tunisia                       │  │
│  │     15 Rajab 1447 H - 15 January 2026            │  │
│  │  ─────────────────────────────────────────────── │  │
│  │  🌅 Fajr   ☀️ Dhuhr   🌤️ Asr   🌆 Maghrib  🌙 Isha │  │
│  │  05:30     12:45     15:30    18:00      19:30   │  │
│  │  ─────────────────────────────────────────────── │  │
│  │  Sunrise: 06:45  Midnight: 00:30  Imsak: 05:20  │  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

## 🔧 Configuration

### 1. Route déjà configurée
La route `/prayer-times` a été ajoutée dans `routes/web.php` :
```php
Route::get('/prayer-times', [\App\Http\Controllers\prayerController::class, 'getPrayerTimes']);
```

### 2. Contrôleur existant
Le fichier `app/Http/Controllers/prayerController.php` utilise l'API Aladhan :
```php
public function getPrayerTimes(Request $request)
{
    $city = $request->city ?? 'Tunis';
    $country = $request->country ?? 'Tunisia';

    $response = Http::get('http://api.aladhan.com/v1/timingsByCity', [
        'city' => $city,
        'country' => $country,
        'method' => 2  // طريقة الحساب
    ]);

    return $response->json();
}
```

### 3. API Aladhan (Gratuite)
- ✅ **Pas de clé API nécessaire**
- ✅ **Gratuit et illimité**
- ✅ **Données précises**
- ✅ **Support de toutes les villes du monde**

## 🧪 Tests à effectuer

### Étape 1: Actualiser la page
```bash
Ctrl+F5
```

### Étape 2: Vérifier l'affichage
1. Allez sur `/home` ou `/weather-view`
2. ✅ Vous devriez voir le widget de prière avec le sélecteur de ville

### Étape 3: Charger les horaires
1. Sélectionnez une ville (ex: **Tunis**)
2. Cliquez sur "🕌 Charger"
3. ✅ Les horaires s'affichent avec :
   - 📍 Nom de la ville
   - 📅 Date grégorienne et hijri
   - 🕌 5 prières principales
   - ⏰ Horaires supplémentaires (Sunrise, Midnight, Imsak)

### Étape 4: Tester d'autres villes
Essayez :
- 🇹🇳 **Tunis**
- 🇫🇷 **Paris**
- 🇪🇬 **Cairo**
- 🇸🇦 **Mecca**
- 🇸🇦 **Medina**
- 🇹🇷 **Istanbul**
- 🇦🇪 **Dubai**
- 🇬🇧 **London**

## 📊 Données affichées

### Prières principales
- **Fajr** (الفجر) - Prière de l'aube
- **Dhuhr** (الظهر) - Prière du midi
- **Asr** (العصر) - Prière de l'après-midi
- **Maghrib** (المغرب) - Prière du coucher du soleil
- **Isha** (العشاء) - Prière de la nuit

### Horaires supplémentaires
- **Sunrise** - Lever du soleil
- **Midnight** - Minuit islamique
- **Imsak** - Début du jeûne (Ramadan)

### Informations de date
- Date grégorienne (ex: 15 January 2026)
- Date hijri (ex: 15 Rajab 1447 H)

## 🎯 Fonctionnalités

### 1. États du widget
- **Default** : Message d'invitation à sélectionner une ville
- **Loading** : Animation de chargement
- **Success** : Affichage des horaires
- **Error** : Message d'erreur en cas de problème

### 2. Highlight automatique
Le widget met en évidence la prière actuelle ou la prochaine prière (±30 minutes) avec :
- Bordure verte brillante (`ring-4 ring-green-400`)
- Effet de zoom (`scale-105`)

### 3. Responsive
- **Mobile** : Grille 2 colonnes
- **Desktop** : Grille 5 colonnes

## 💡 Personnalisation

### Ajouter plus de villes
Dans `home.blade.php`, ajoutez des options au select :
```html
<option value="Casablanca,Morocco">🇲🇦 Casablanca</option>
<option value="Algiers,Algeria">🇩🇿 Algiers</option>
<option value="Riyadh,Saudi Arabia">🇸🇦 Riyadh</option>
```

### Changer la méthode de calcul
Dans `prayerController.php`, modifiez le paramètre `method` :
```php
'method' => 2  // 2 = ISNA (Islamic Society of North America)
```

Méthodes disponibles :
- **0** : Shia Ithna-Ashari
- **1** : University of Islamic Sciences, Karachi
- **2** : Islamic Society of North America (ISNA)
- **3** : Muslim World League (MWL)
- **4** : Umm al-Qura, Makkah
- **5** : Egyptian General Authority of Survey
- **7** : Institute of Geophysics, University of Tehran
- **8** : Gulf Region
- **9** : Kuwait
- **10** : Qatar
- **11** : Majlis Ugama Islam Singapura, Singapore
- **12** : Union Organization islamic de France
- **13** : Diyanet İşleri Başkanlığı, Turkey

### Auto-charger au chargement de la page
Décommentez cette ligne dans le script :
```javascript
document.addEventListener('DOMContentLoaded', function() {
    loadPrayerTimes(); // ← Décommentez cette ligne
});
```

### Changer les couleurs
Modifiez les classes Tailwind dans `home.blade.php` :
```html
<!-- Exemple pour Fajr -->
<div class="bg-gradient-to-br from-blue-50 to-blue-100 ...">
```

## 🐛 Dépannage

### Problème 1: Widget ne s'affiche pas
**Solution** :
```bash
php artisan cache:clear
php artisan view:clear
```
Puis actualisez (Ctrl+F5)

### Problème 2: Erreur lors du chargement
**Causes possibles** :
1. Route manquante
2. Contrôleur introuvable
3. API Aladhan indisponible

**Solution** :
1. Vérifiez que la route existe dans `routes/web.php`
2. Vérifiez que `prayerController.php` existe
3. Testez l'API directement : `http://api.aladhan.com/v1/timingsByCity?city=Tunis&country=Tunisia`

### Problème 3: Horaires incorrects
**Solution** :
Changez la méthode de calcul dans `prayerController.php` selon votre région.

## 🔗 Ressources

- [API Aladhan Documentation](https://aladhan.com/prayer-times-api)
- [Méthodes de calcul](https://aladhan.com/calculation-methods)
- [Calendrier Hijri](https://aladhan.com/hijri-gregorian-calendar)

## 📝 Prochaines améliorations possibles

1. ✨ Notification avant chaque prière
2. 🔔 Alarme sonore (Adhan)
3. 📅 Calendrier mensuel des horaires
4. 🌍 Géolocalisation automatique
5. 💾 Sauvegarder la ville favorite
6. 📊 Graphique des horaires sur le mois
7. 🌙 Compteur jusqu'à la prochaine prière
8. 📱 Mode widget compact

---

**Votre widget d'horaires de prière est maintenant moderne et intégré avec le thème de votre application ! 🕌💖**

