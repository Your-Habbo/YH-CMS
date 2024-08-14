<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\PjaxTrait;


class HomeController extends Controller
{   
    use PjaxTrait;
    
    public function index()
    {
        $featuredNews = collect();
        $latestNews = collect();

        if (Schema::hasColumn('news', 'is_featured')) {
            $featuredNews = News::where('is_featured', true)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
        }

        if ($featuredNews->isEmpty()) {
            // If no featured news or column doesn't exist, just get the latest news
            $featuredNews = News::orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
        }

        $latestNews = News::orderBy('created_at', 'desc')
                          ->take(10)
                          ->get();

        // Example upcoming events (replace with actual data from your database)
        $upcomingEvents = [
            ['name' => 'Summer Party', 'date' => 'Aug 15, 2024'],
            ['name' => 'DJ Battle', 'date' => 'Aug 20, 2024'],
            ['name' => 'Habbo Trivia Night', 'date' => 'Aug 25, 2024'],
        ];

        return $this->view('index', compact('featuredNews', 'latestNews', 'upcomingEvents'));
    }
}