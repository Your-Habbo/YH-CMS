<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SongSearchController extends Controller
{
    public function searchSong(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }

        // Get Spotify API token
        $client_id = env('SPOTIFY_CLIENT_ID');
        $client_secret = env('SPOTIFY_CLIENT_SECRET');
        $token_response = Http::asForm()->withBasicAuth($client_id, $client_secret)->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'client_credentials',
        ]);

        if (!$token_response->successful()) {
            return response()->json(['error' => 'Unable to authenticate with Spotify'], 500);
        }

        $access_token = $token_response->json()['access_token'];

        // Query Spotify API for tracks
        $search_response = Http::withToken($access_token)->get('https://api.spotify.com/v1/search', [
            'q' => $query,
            'type' => 'track',
            'limit' => 5,
        ]);

        if (!$search_response->successful()) {
            return response()->json(['error' => 'Failed to retrieve data from Spotify'], 500);
        }

        return $search_response->json();
    }
}
