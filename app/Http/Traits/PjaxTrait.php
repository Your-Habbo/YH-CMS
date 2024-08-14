<?php

namespace App\Http\Traits;

trait PjaxTrait
{
    protected function view($view, $data = [])
    {
        if (request()->header('X-PJAX') || request()->ajax()) {
            return view($view, $data)->render();
        }
    
        // Check if the content is already loaded, if not, load the appropriate view
        if (view()->exists('layouts.app')) {
            return view('layouts.app')->with('content', view($view, $data));
        }
    
        // Fallback if the view does not exist
        return view('layouts.app')->with('content', view($view, $data));
    }
    
}