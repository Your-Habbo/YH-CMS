<?php

namespace App\Http\Controllers;

use App\Http\Traits\PjaxTrait;

class ErrorController extends Controller
{
    use PjaxTrait;

    public function notFound()
    {
        return $this->notFound();
    }

    public function notAuthorized()
    {
        return $this->notAuthorized();
    }

    public function serverError()
    {
        return $this->serverError();
    }
}