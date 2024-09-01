<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Http\Traits\PjaxTrait;

class DashboardController extends Controller
{
    use PjaxTrait;
    
    public function index()
    {
        $userCount = User::count();
        return $this->view('admin.dashboard', compact('userCount'));
    }
}
