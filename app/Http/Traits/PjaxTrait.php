<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait PjaxTrait
{
    protected function view($view, $data = [])
    {
        if (request()->ajax() || request()->header('X-PJAX')) {
            $html = view($view, $data)->render();
            return new JsonResponse([
                'html' => $html,
                'url' => request()->url(),
                'title' => $data['title'] ?? config('app.name')
            ]);
        }
    
        return view('layouts.app')->with('content', view($view, $data));
    }
}