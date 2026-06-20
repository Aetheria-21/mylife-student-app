<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
{
    // Coordonnées fixes de Tunis
    $lat = 36.8065;
    $lon = 10.1815;

    $response = Http::get('https://api.open-meteo.com/v1/forecast', [
        'latitude'       => $lat,
        'longitude'      => $lon,
        'current'        => 'temperature_2m,relative_humidity_2m,apparent_temperature,wind_speed_10m,weather_code',
        'wind_speed_unit'=> 'kmh',
        'timezone'       => 'Africa/Tunis',
    ]);

    if ($response->failed()) {
        return response()->json(['error' => 'Erreur météo'], 500);
    }

    $data = $response->json();
    $current = $data['current'];

    // Reformater pour garder la même structure qu'avant dans le Blade
    return response()->json([
        'location' => [
            'name'      => 'Tunis',
            'country'   => 'Tunisia',
            'localtime' => $current['time'],
        ],
        'current' => [
            'temp_c'      => $current['temperature_2m'],
            'feelslike_c' => $current['apparent_temperature'],
            'humidity'    => $current['relative_humidity_2m'],
            'wind_kph'    => $current['wind_speed_10m'],
            'condition'   => [
                'text' => $this->getConditionText($current['weather_code']),
            ],
        ],
    ]);
}

private function getConditionText(int $code): string
{
    return match(true) {
        $code === 0              => 'Ciel dégagé',
        in_array($code, [1,2,3])=> 'Partiellement nuageux',
        in_array($code, [45,48])=> 'Brouillard',
        in_array($code, [51,53,55]) => 'Bruine',
        in_array($code, [61,63,65]) => 'Pluie',
        in_array($code, [71,73,75]) => 'Neige',
        in_array($code, [80,81,82]) => 'Averses',
        in_array($code, [95,96,99]) => 'Orage',
        default                  => 'Conditions variables',
    };
}

    /**
     * Afficher la météo dans la page d'accueil - Fixed Tunisia
     */
    public function showWithCalendar(Request $request)
    {
        $city = 'Tunis,TN'; // Fixed to Tunisia

        try {
            // Récupérer les données météo
            $weatherResponse = Http::get('https://api.weatherapi.com/v1/current.json', [
                'key' => config('services.weatherapi.key'),
                'q' => $city,
                'lang' => 'fr'
            ]);

            if ($weatherResponse->failed()) {
                $weather = null;
                $weatherError = 'Impossible de récupérer les données météo';
            } else {
                $weather = $weatherResponse->json();
                $weatherError = null;
            }
        } catch (\Exception $e) {
            $weather = null;
            $weatherError = 'Erreur: ' . $e->getMessage();
        }

        // Récupérer les événements du calendrier (même logique que CalendarEventController)
        $events = $this->getCalendarEvents();

        return view('home', compact('weather', 'weatherError', 'events'));
    }

    /**
     * Récupérer les événements du calendrier
     */
    private function getCalendarEvents()
    {
        try {
            $user = auth()->user();

            if (!$user || !$user->google_token) {
                return [];
            }

            $client = new \Google\Client();
            $client->setClientId(env('GOOGLE_CLIENT_ID'));
            $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
            $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
            $client->setAccessToken($user->google_token);

            if ($client->isAccessTokenExpired()) {
                if ($user->google_refresh_token) {
                    $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                    $newToken = $client->getAccessToken();
                    $user->google_token = $newToken['access_token'];
                    $user->save();
                } else {
                    return [];
                }
            }

            $service = new \Google\Service\Calendar($client);

            $timeMin = (new \DateTime())->modify('-6 months')->format(\DateTime::RFC3339);
            $timeMax = (new \DateTime())->modify('+6 months')->format(\DateTime::RFC3339);

            $eventsData = $service->events->listEvents('primary', [
                'maxResults' => 2500,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => $timeMin,
                'timeMax' => $timeMax,
            ]);

            $events = [];
            foreach ($eventsData->getItems() as $event) {
                $events[] = [
                    'id' => $event->id,
                    'title' => $event->getSummary(),
                    'start' => $event->start->dateTime ?? $event->start->date,
                    'end' => $event->end->dateTime ?? $event->end->date,
                ];
            }

            return $events;
        } catch (\Exception $e) {
            \Log::error('Weather controller calendar error: ' . $e->getMessage());
            return [];
        }
    }
}
