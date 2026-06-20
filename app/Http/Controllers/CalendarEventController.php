<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Homework;
use App\Models\Internship;
use App\Models\Debt;
use App\Models\UserEmotionTip;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use DateTime;
class CalendarEventController extends Controller
{
    /**
     * Obtenir le client Google avec gestion du token
     */
    protected function getClient()
    {
        $user = Auth::user();

        if (!$user->google_token) {
            throw new \Exception('Jeton Google introuvable. Veuillez reconnecter votre compte Google.');
        }

        $client = new GoogleClient();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->setAccessToken($user->google_token);

        // Vérifier et rafraîchir le token si expiré
        if ($client->isAccessTokenExpired()) {
            if ($user->google_refresh_token) {
                $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                $newToken = $client->getAccessToken();
                $user->google_token = $newToken['access_token'];
                $user->save();
            } else {
                throw new \Exception('Jeton de rafraîchissement introuvable. Veuillez reconnecter votre compte Google.');
            }
        }

        return $client;
    }

    /**
     * Afficher la page d'accueil avec les événements du calendrier
     */
    public function index()
    {
        $user = Auth::user();
        $emotionAdvice = $this->resolveEmotionAdvice($user);
        $taskProgress = $this->resolveTaskProgress($user);
        $upcomingDeadlines = $this->resolveUpcomingDeadlines($user);

        try {
            $service = new Calendar($this->getClient());

            // Récupérer les événements des 6 derniers mois jusqu'à 6 mois dans le futur
            $timeMin = (new DateTime())->modify('-6 months')->format(DateTime::RFC3339);
            $timeMax = (new DateTime())->modify('+6 months')->format(DateTime::RFC3339);

            $eventsData = $service->events->listEvents('primary', [
                'maxResults' => 2500, // Augmenté pour afficher plus d'événements
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => $timeMin, // 6 mois dans le passé
                'timeMax' => $timeMax, // 6 mois dans le futur
            ]);

            $events = [];
            foreach ($eventsData->getItems() as $event) {
                $eventData = [
                    'id' => $event->id,
                    'title' => $event->getSummary(),
                    'start' => $event->start->dateTime ?? $event->start->date,
                    'end' => $event->end->dateTime ?? $event->end->date,
                ];

                // Ajouter la description si elle existe
                if ($event->getDescription()) {
                    $eventData['description'] = $event->getDescription();
                }

                // Ajouter la couleur si elle existe
                if ($event->getColorId()) {
                    $eventData['color'] = $this->getColorFromId($event->getColorId());
                }

                $events[] = $eventData;
            }

            Log::info('Calendar events loaded:', ['count' => count($events)]);

            return view('home', compact('events', 'emotionAdvice', 'taskProgress', 'upcomingDeadlines'));
        } catch (\Exception $e) {
            Log::error('Calendar index error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return view('home', [
                'events' => [],
                'emotionAdvice' => $emotionAdvice,
                'taskProgress' => $taskProgress,
                'upcomingDeadlines' => $upcomingDeadlines,
            ]);
        }
    }

    private function resolveUpcomingDeadlines($user): array
    {
        if (! $user) {
            return [];
        }

        $today = Carbon::today();
        $items = collect();

        Task::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '>=', $today)
            ->orderBy('due_date')
            ->limit(5)
            ->get()
            ->each(function ($t) use ($items) {
                $items->push([
                    'type'     => 'task',
                    'icon'     => Task::iconFor($t->category),
                    'title'    => $t->title,
                    'subtitle' => 'Tâche · ' . (Task::CATEGORIES[$t->category] ?? ucfirst($t->category)),
                    'due_date' => $t->due_date,
                    'url'      => route('tasks.index'),
                    'color'    => '#3b82f6',
                ]);
            });

        Homework::where('user_id', $user->id)
            ->where('is_done', false)
            ->whereNotNull('due_date')
            ->where('due_date', '>=', $today)
            ->orderBy('due_date')
            ->limit(5)
            ->get()
            ->each(function ($h) use ($items) {
                $items->push([
                    'type'     => 'homework',
                    'icon'     => '📚',
                    'title'    => $h->title,
                    'subtitle' => 'Devoir',
                    'due_date' => $h->due_date,
                    'url'      => route('study.index'),
                    'color'    => '#8b5cf6',
                ]);
            });

        Internship::where('user_id', $user->id)
            ->where('status', 'Pending')
            ->whereNotNull('response_date')
            ->where('response_date', '>=', $today)
            ->orderBy('response_date')
            ->limit(5)
            ->get()
            ->each(function ($i) use ($items) {
                $items->push([
                    'type'     => 'internship',
                    'icon'     => '💼',
                    'title'    => $i->company,
                    'subtitle' => 'Réponse attendue · ' . $i->position,
                    'due_date' => $i->response_date,
                    'url'      => route('internship.index'),
                    'color'    => '#f59e0b',
                ]);
            });

        Debt::where('user_id', $user->id)
            ->where('status', '!=', 'paid')
            ->whereNotNull('due_date')
            ->where('due_date', '>=', $today)
            ->orderBy('due_date')
            ->limit(5)
            ->get()
            ->each(function ($d) use ($items) {
                $remaining = (float) $d->total_amount - (float) $d->paid_amount;
                $items->push([
                    'type'     => 'debt',
                    'icon'     => '💰',
                    'title'    => $d->creditor,
                    'subtitle' => 'Dette · ' . number_format($remaining, 2) . ' TND',
                    'due_date' => $d->due_date,
                    'url'      => route('finance.index'),
                    'color'    => '#ef4444',
                ]);
            });

        return $items->sortBy('due_date')->take(5)->values()->all();
    }

    private function resolveEmotionAdvice($user): array
    {
        $emotionAdvice = UserEmotionTip::defaultTips();

        if (! $user) {
            return $emotionAdvice;
        }

        foreach ($user->emotionTips()->orderBy('emotion')->orderBy('tip_order')->get() as $tip) {
            if (isset($emotionAdvice[$tip->emotion][$tip->tip_order - 1])) {
                $emotionAdvice[$tip->emotion][$tip->tip_order - 1] = $tip->tip_text;
            }
        }

        return $emotionAdvice;
    }

    private function resolveTaskProgress($user): array
    {
        $empty = [
            'total' => 0,
            'pending' => 0,
            'completed' => 0,
            'completionRate' => 0,
            'categories' => [],
        ];

        if (! $user) {
            return $empty;
        }

        $tasks = $user->tasks()->get();
        $total = $tasks->count();
        $pending = $tasks->where('status', 'pending')->count();
        $completed = $tasks->where('status', 'completed')->count();
        $topCategories = $tasks
            ->groupBy('category')
            ->map(fn ($group, $category) => [
                'key' => $category,
                'label' => Task::CATEGORIES[$category] ?? ucfirst($category),
                'icon' => Task::iconFor($category),
                'count' => $group->count(),
            ])
            ->sortByDesc('count')
            ->take(4)
            ->values()
            ->all();

        return [
            'total' => $total,
            'pending' => $pending,
            'completed' => $completed,
            'completionRate' => $total > 0 ? (int) round(($completed / $total) * 100) : 0,
            'categories' => $topCategories,
        ];
    }

    /**
     * Obtenir la couleur à partir de l'ID de couleur Google
     */
    private function getColorFromId($colorId)
    {
        $colors = [
            '1' => '#a4bdfc', // Lavender
            '2' => '#7ae7bf', // Sage
            '3' => '#dbadff', // Grape
            '4' => '#ff887c', // Flamingo
            '5' => '#fbd75b', // Banana
            '6' => '#ffb878', // Tangerine
            '7' => '#46d6db', // Peacock
            '8' => '#e1e1e1', // Graphite
            '9' => '#5484ed', // Blueberry
            '10' => '#51b749', // Basil
            '11' => '#dc2127', // Tomato
        ];

        return $colors[$colorId] ?? null;
    }

    /**
     * Créer un nouvel événement
     */
    public function store(Request $request)
    {
        try {
            // Log pour déboguer
            Log::info('Calendar store request:', $request->all());

            // Validation simple sans règles strictes
            $title = $request->input('title');
            $start = $request->input('start');
            $end = $request->input('end');

            if (!$title || !$start) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le titre et la date de début sont requis'
                ], 400);
            }

            $service = new Calendar($this->getClient());

            // Convertir les dates au format correct
            $startDateTime = new DateTime($start);
            $endDateTime = $end ? new DateTime($end) : (clone $startDateTime)->modify('+1 hour');

            // Créer l'événement
            $event = new Event([
                'summary' => $title,
                'description' => $request->input('description', ''),
                'start' => [
                    'dateTime' => $startDateTime->format(DateTime::RFC3339),
                    'timeZone' => 'Africa/Tunis',
                ],
                'end' => [
                    'dateTime' => $endDateTime->format(DateTime::RFC3339),
                    'timeZone' => 'Africa/Tunis',
                ],
            ]);

            $createdEvent = $service->events->insert('primary', $event);

            Log::info('Event created successfully:', ['id' => $createdEvent->id]);

            return response()->json([
                'success' => true,
                'id' => $createdEvent->id,
                'title' => $createdEvent->summary,
                'start' => $createdEvent->start->dateTime,
                'end' => $createdEvent->end->dateTime,
            ]);
        } catch (\Exception $e) {
            Log::error('Calendar store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Mettre à jour un événement existant
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Calendar update request:', ['id' => $id, 'data' => $request->all()]);

            $title = $request->input('title');
            $start = $request->input('start');
            $end = $request->input('end');

            if (!$title || !$start) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le titre et la date de début sont requis'
                ], 400);
            }

            $service = new Calendar($this->getClient());

            // Récupérer l'événement existant
            $event = $service->events->get('primary', $id);

            // Mettre à jour les propriétés
            $event->setSummary($title);

            if ($request->has('description')) {
                $event->setDescription($request->input('description'));
            }

            // Convertir et mettre à jour les dates
            $startDateTime = new DateTime($start);
            $endDateTime = $end ? new DateTime($end) : (clone $startDateTime)->modify('+1 hour');

            $startEvent = new \Google_Service_Calendar_EventDateTime();
            $startEvent->setDateTime($startDateTime->format(DateTime::RFC3339));
            $startEvent->setTimeZone('Africa/Tunis');
            $event->setStart($startEvent);

            $endEvent = new \Google_Service_Calendar_EventDateTime();
            $endEvent->setDateTime($endDateTime->format(DateTime::RFC3339));
            $endEvent->setTimeZone('Africa/Tunis');
            $event->setEnd($endEvent);

            // Sauvegarder les modifications
            $updatedEvent = $service->events->update('primary', $id, $event);

            Log::info('Event updated successfully:', ['id' => $updatedEvent->id]);

            return response()->json([
                'success' => true,
                'id' => $updatedEvent->id,
                'title' => $updatedEvent->summary,
                'start' => $updatedEvent->start->dateTime ?? $updatedEvent->start->date,
                'end' => $updatedEvent->end->dateTime ?? $updatedEvent->end->date,
            ]);
        } catch (\Exception $e) {
            Log::error('Calendar update error: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un événement
     */
    public function destroy($id)
    {
        try {
            $service = new Calendar($this->getClient());
            $service->events->delete('primary', $id);

            return response()->json([
                'success' => true,
                'message' => 'Événement supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Calendar destroy error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Échec de la suppression de l\'événement : ' . $e->getMessage()
            ], 500);
        }
    }
}