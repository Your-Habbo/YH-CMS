<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Traits\PjaxTrait;

class PageController extends Controller
{
    use PjaxTrait;

    /**
     * Display a listing of the pages.
     */
    public function index()
    {
        $pages = Page::all();

        return $this->view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        return $this->view('admin.pages.create');
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'layout' => 'required|string',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]);

        Page::create($request->all());

        if ($request->pjax()) {
            return response()->json([
                'redirect' => route('admin.pages.index')
            ]);
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified page.
     */
    public function show(Page $page)
    {
        return $this->view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page)
    {
        return $this->view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page in storage.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'layout' => 'required|string',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]);

        $page->update($request->all());

        if ($request->pjax()) {
            return response()->json([
                'redirect' => route('admin.pages.index')
            ]);
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        if (request()->pjax()) {
            return response()->json([
                'redirect' => route('admin.pages.index')
            ]);
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }
}
