# 🌤️ Guide de l'API Météo

## ✅ Corrections effectuées

### 1. **WeatherController.php**
- ✅ Corrigé l'erreur `compact('home')` → `compact('weather', 'weatherError', 'events')`
- ✅ Ajout de la méthode `showWithCalendar()` pour afficher météo + calendrier
- ✅ Gestion des erreurs avec try/catch
- ✅ Récupération automatique des événements du calendrier
- ✅ Support de la ville par défaut (Tunis)

### 2. **routes/web.php**
- ✅ Ajout de la route `/weather-view` manquante
- ✅ Route nommée `weather.view`

### 3. **home.blade.php**
- ✅ Widget météo moderne avec le thème rose
- ✅ Design responsive (mobile + desktop)
- ✅ Affichage des informations détaillées :
  - 📍 Ville et pays
  - 🌡️ Température actuelle
  - 🌤️ Conditions météo
  - ⏰ Heure locale
  - 💨 Vitesse du vent
  - 💧 Humidité
  - 🌡️ Température ressentie
- ✅ Formulaire de recherche stylisé
- ✅ Gestion des erreurs avec messages clairs

## 🎨 Design du Widget Météo

### Couleurs utilisées
- **Fond** : Dégradé rose-violet (`from-pink-100 to-purple-100`)
- **Carte** : Blanc avec ombre
- **Texte principal** : Rose (`text-pink-600`)
- **Bouton** : Rose avec effet hover
- **Bordures** : Rose clair (`border-pink-300`)

### Responsive
- **Mobile** : Disposition verticale (colonne)
- **Desktop** : Disposition horizontale (ligne)

## 🔧 Configuration

### 1. Obtenir une clé API WeatherAPI

1. Allez sur [https://www.weatherapi.com/](https://www.weatherapi.com/)
2. Créez un compte gratuit
3. Copiez votre clé API

### 2. Configurer le fichier .env

Ajoutez cette ligne dans votre fichier `.env` :
```env
WEATHER_API_KEY=votre_cle_api_ici
```

### 3. Vérifier la configuration

Le fichier `config/services.php` contient déjà :
```php
'weatherapi' => [
    'key' => env('WEATHER_API_KEY'),
],
```

## 🧪 Test

### Test 1: Affichage par défaut
1. Allez sur `/home`
2. Vous devriez voir le widget météo avec "Recherchez une ville"

### Test 2: Rechercher une ville
1. Tapez "Paris" dans le champ de recherche
2. Cliquez sur "🌤️ Météo"
3. ✅ La météo de Paris s'affiche

### Test 3: Vérifier les données affichées
- ✅ Nom de la ville
- ✅ Pays
- ✅ Heure locale
- ✅ Température
- ✅ Conditions météo
- ✅ Vent, humidité, température ressentie

## 📊 Données disponibles

L'API WeatherAPI retourne :
```json
{
  "location": {
    "name": "Paris",
    "country": "France",
    "localtime": "2026-01-04 19:30"
  },
  "current": {
    "temp_c": 15.5,
    "condition": {
      "text": "Partiellement nuageux"
    },
    "wind_kph": 12.5,
    "humidity": 65,
    "feelslike_c": 14.2
  }
}
```

## 🎯 Routes disponibles

| Route | Méthode | Description |
|-------|---------|-------------|
| `/home` | GET | Calendrier seul (sans météo) |
| `/weather-view` | GET | Calendrier + météo |
| `/weather` | GET | API JSON météo uniquement |

## 💡 Utilisation

### Afficher la météo d'une ville spécifique
```
http://127.0.0.1:8000/weather-view?city=Paris
http://127.0.0.1:8000/weather-view?city=London
http://127.0.0.1:8000/weather-view?city=Tokyo
```

### Obtenir les données JSON
```
http://127.0.0.1:8000/weather?city=Paris
```

## 🐛 Dépannage

### Problème 1: "Impossible de récupérer les données météo"
**Causes possibles** :
1. Clé API manquante ou invalide
2. Ville introuvable
3. Limite de requêtes dépassée (plan gratuit)

**Solution** :
1. Vérifiez votre clé API dans `.env`
2. Vérifiez l'orthographe de la ville
3. Attendez quelques minutes si limite dépassée

### Problème 2: Le widget ne s'affiche pas
**Solution** :
1. Videz le cache : `php artisan cache:clear`
2. Videz les vues : `php artisan view:clear`
3. Actualisez la page (Ctrl+F5)

### Problème 3: Erreur 500
**Solution** :
1. Vérifiez les logs : `storage/logs/laravel.log`
2. Vérifiez que la clé API est configurée
3. Vérifiez la connexion internet

## 🎨 Personnalisation

### Changer la ville par défaut
Dans `WeatherController.php`, ligne 31 :
```php
$city = $request->get('city', 'Paris'); // Au lieu de 'Tunis'
```

### Changer la langue
Dans `WeatherController.php`, ligne 38 :
```php
'lang' => 'ar', // Arabe
'lang' => 'en', // Anglais
'lang' => 'fr', // Français (actuel)
```

### Ajouter plus de données météo
Vous pouvez afficher :
- Pression atmosphérique : `$weather['current']['pressure_mb']`
- UV Index : `$weather['current']['uv']`
- Visibilité : `$weather['current']['vis_km']`
- Direction du vent : `$weather['current']['wind_dir']`

## 📝 Prochaines améliorations possibles

1. ✨ Prévisions sur 3-7 jours
2. 🌍 Géolocalisation automatique
3. 📊 Graphiques de température
4. 🔔 Alertes météo
5. 💾 Sauvegarder les villes favorites
6. 🌙 Mode nuit/jour selon l'heure locale
7. 🎨 Icônes météo animées

## 🔗 Ressources

- [Documentation WeatherAPI](https://www.weatherapi.com/docs/)
- [Plans et tarifs](https://www.weatherapi.com/pricing.aspx)
- [Codes de condition météo](https://www.weatherapi.com/docs/weather_conditions.json)

