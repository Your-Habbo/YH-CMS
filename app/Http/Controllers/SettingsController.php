<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function settings()
    {
        $user = auth()->user();
        return view('settings.index', compact('user'));
    }
}
