<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Forum\ForumCategory;
use App\Models\Admin\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ForumCategoryController extends Controller
{
    public function index()
    {   
        
        $categories = ForumCategory::all();
        return view('admin.forum.categories.index', compact('categories'));
    }

    public function create()
    {   
        $images = Image::all();
        return view('admin.forum.categories.create', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:forum_categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|string', // Image path will be stored as a string
        ]);

        ForumCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $request->image, // Store the selected image path
        ]);

        return redirect()->route('admin.forum-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(ForumCategory $forumCategory)
    {
        $images = Image::all(); // Fetch all images
        return view('admin.forum.categories.edit', compact('forumCategory', 'images'));
    }

    public function update(Request $request, ThreadTag $forumTag)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('thread_tags')->ignore($forumTag->id), // Ignore the current tag ID in the unique check
            ],
            'color' => 'required|string|size:7', // Ensure it is a valid hex code
        ]);
    
        // Update the tag
        $forumTag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color' => $request->color,
            'is_help_tag' => $request->has('is_help_tag'),
        ]);
    
        return redirect()->route('admin.forum-tags.index')->with('success', 'Tag updated successfully.');
    }

    
    public function destroy(ForumCategory $forumCategory)
    {
        $forumCategory->delete();
        return redirect()->route('admin.forum-categories.index')->with('success', 'Category deleted successfully.');
    }
}

