<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Traits\PjaxTrait;

class PageController extends Controller
{
    use PjaxTrait;

    /**
     * Display the specified page.
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        // Use the layout defined in the page record
        $layout = $page->layout;

        // Pass the layout to the view
        return $this->view('pages.show', compact('page', 'layout'), 200, $layout);
    }
}
