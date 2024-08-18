<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait PjaxTrait
{
    protected function view($view, $data = [], $status = 200)
    {
        if (request()->ajax() || request()->header('X-PJAX')) {
            $html = view($view, $data)->render();
            return new JsonResponse([
                'html' => $html,
                'url' => request()->url(),
                'title' => $data['title'] ?? config('app.name')
            ], $status);
        }
    
        return view('layouts.app')->with('content', view($view, $data));
    }

    protected function notFound($data = [])
    {
        $data['title'] = '404 - Page Not Found';
        return $this->view('errors.404', $data, 404);
    }

    protected function notAuthorized($data = [])
    {
        $data['title'] = '403 - Not Authorized';
        return $this->view('errors.403', $data, 403);
    }

    protected function serverError($data = [])
    {
        $data['title'] = '500 - Server Error';
        return $this->view('errors.500', $data, 500);
    }
}