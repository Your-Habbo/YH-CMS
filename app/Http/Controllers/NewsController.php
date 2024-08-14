<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display a listing of the news articles.
     */
    public function index()
    {
        $news = News::with('author')->orderBy('published_at', 'desc')->paginate(10);
        return view('news.index', compact('news'));
    }

    /**
     * Show the form for creating a new news article.
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Store a newly created news article in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image',
        ]);

        $slug = Str::slug($request->title);

        $news = News::create([
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $slug,
            'user_id' => auth()->id(),
            'published_at' => $request->has('publish') ? now() : null,
            'image' => $request->file('image') ? $request->file('image')->store('news-images', 'public') : null,
        ]);

        return redirect()->route('news.show', $news->slug)->with('success', 'News article created successfully.');
    }

    /**
     * Display the specified news article.
     */
    public function show($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        return view('news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified news article.
     */
    public function edit(News $news)
    {
        return view('news.edit', compact('news'));
    }

    /**
     * Update the specified news article in storage.
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image',
        ]);

        $news->update([
            'title' => $request->title,
            'content' => $request->content,
            'slug' => Str::slug($request->title),
            'image' => $request->file('image') ? $request->file('image')->store('news-images', 'public') : $news->image,
            'published_at' => $request->has('publish') ? now() : $news->published_at,
        ]);

        return redirect()->route('news.show', $news->slug)->with('success', 'News article updated successfully.');
    }

    /**
     * Remove the specified news article from storage.
     */
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('news.index')->with('success', 'News article deleted successfully.');
    }


    /**
     * Search for news articles.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        $news = News::where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                    ->orderBy('published_at', 'desc')
                    ->paginate(10);

        return view('news.index', compact('news', 'query'));
    }
}
