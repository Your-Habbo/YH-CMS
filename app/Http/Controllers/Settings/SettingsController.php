<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Traits\PjaxTrait;

class SettingsController extends Controller
{   
    use PjaxTrait;  

    public function settings()
    {
        $user = Auth::user();
        return $this->view('settings.account', compact('user'));
    }

    public function notifications()
    {
        return $this->view('settings.notifications');
    }

}