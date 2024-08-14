<?php

namespace App\Http\Controllers;

use App\Http\Traits\PjaxTrait;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use PjaxTrait;

    /**
     * Display the about page.
     */
    public function about()
    {
        return $this->view('pages.about');
    }

    /**
     * Display the disclaimer page.
     */
    public function disclaimer()
    {
        return $this->view('pages.disclaimer');
    }

    /**
     * Display the terms page.
     */
    public function terms()
    {
        return $this->view('pages.terms');
    }

    /**
     * Display the privacy page.
     */
    public function privacy()
    {
        return $this->view('pages.privacy');
    }
}
