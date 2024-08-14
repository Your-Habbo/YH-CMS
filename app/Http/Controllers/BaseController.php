<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    protected function view($view, $data = [])
    {
        if (request()->header('X-PJAX')) {
            return view($view, $data);
        }

        return view('layouts.app')->nest('content', $view, $data);
    }
}