<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Forum\ThreadTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ThreadTagController extends Controller
{
    public function index()
    {
        $tags = ThreadTag::all();
        return view('admin.forum.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.forum.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:thread_tags,name',
            'color' => 'required|string|size:7', // Ensure it is a valid hex code
        ]);

        ThreadTag::create([
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name),
            'color' => $request->color,
            'is_help_tag' => $request->has('is_help_tag'),
        ]);

        return redirect()->route('admin.forum-tags.index')->with('success', 'Tag created successfully.');
    }



    public function update(Request $request, ThreadTag $forumTag)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('thread_tags')->ignore($forumTag->id), // Ignore the current tag ID
            ],
            'color' => 'required|string|size:7', // Ensure it is a valid hex code
        ]);
    
        $forumTag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color' => $request->color,
            'is_help_tag' => $request->has('is_help_tag'),
        ]);
    
        return redirect()->route('admin.forum-tags.index')->with('success', 'Tag updated successfully.');
    }
    

    public function edit($id)
    {
        $forumTag = ThreadTag::findOrFail($id); // Fetch the tag by ID

        return view('admin.forum.tags.edit', compact('forumTag')); // Pass the tag to the view
    }

    public function destroy(ThreadTag $forumTag) // Use consistent variable name
    {
        $forumTag->delete();
        return redirect()->route('admin.forum-tags.index')->with('success', 'Tag deleted successfully.');
    }

    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (ThreadTag::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
