<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Image;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::all();   

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'News Article'],
        ];

        return view('admin.news.index', compact('news',  'breadcrumbs'));
    }

    public function create()
    {
        $images = Image::all(); // Fetch all images from your Image model
    
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'News', 'url' => route('admin.news.index')],
            ['label' => 'Create New Article'],
        ];
    
        return view('admin.news.create', compact('images', 'breadcrumbs'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'required|string',  // Validate as a string since we're using an image manager
            'published_at' => 'nullable|date',
        ]);

        $news = new News();
        $news->title = $request->title;
        $news->content = $request->content;
        $news->slug = Str::slug($request->title);
        $news->user_id = auth()->id();
        $news->is_featured = $request->has('is_featured');
        $news->published_at = $request->published_at;
        $news->image = $request->image;  // The image path from the image manager

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'News article created successfully.');
    }

    public function edit(News $news)
    {   
        $images = Image::all();

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'News', 'url' => route('admin.news.index')],
            ['label' => 'Edit Article', 'url' => route('admin.news.edit', 'breadcrumbs', $news->id)],
        ];

        return view('admin.news.edit', compact('news', 'images', 'breadcrumbs'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'required|string',  // Validate as a string since we're using an image manager
            'published_at' => 'nullable|date',
        ]);

        $news->title = $request->title;
        $news->content = $request->content;
        $news->slug = Str::slug($request->title);
        $news->is_featured = $request->has('is_featured');
        $news->published_at = $request->published_at;
        $news->image = $request->image;  // The image path from the image manager

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'News article updated successfully.');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'News article deleted successfully.');
    }

    public function preview(Request $request)
    {
        $news = new News();
        $news->title = $request->title;
        $news->content = $request->content;
        $news->slug = Str::slug($request->title);
        $news->image = $request->image;
        $news->published_at = $request->published_at ? Carbon::parse($request->published_at) : now();
        $news->is_featured = $request->has('is_featured');

        return view('admin.news.preview', compact('news'));
    }

}
