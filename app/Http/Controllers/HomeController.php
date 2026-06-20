<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $client = new GoogleClient();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
    $client->setAccessToken($user->google_token);

    if ($client->isAccessTokenExpired() && $user->google_refresh_token) {
        $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
        $user->google_token = $client->getAccessToken()['access_token'];
        $user->save();
    }

     $service = new Calendar($client);

    $eventsData = $service->events->listEvents('primary', [
        'maxResults' => 50,
        'orderBy' => 'startTime',
        'singleEvents' => true,
        'timeMin' => date('c'),
    ]);
  $events = [];

    foreach ($eventsData->getItems() as $event) {
        $events[] = [
            'title' => $event->getSummary(),
            'start' => $event->start->dateTime ?? $event->start->date,
            'end' => $event->end->dateTime ?? $event->end->date,
        ];
    }
    return view('home', compact('events'));
}
}
