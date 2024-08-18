<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    // ... other methods ...

    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Resource not found'], 404);
            }
            return app()->make('App\Http\Controllers\ErrorController')->notFound();
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Access denied'], 403);
            }
            return app()->make('App\Http\Controllers\ErrorController')->notAuthorized();
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($this->isHttpException($e) && $e->getStatusCode() == 500) {
                if ($request->is('api/*')) {
                    return response()->json(['message' => 'Server error'], 500);
                }
                return app()->make('App\Http\Controllers\ErrorController')->serverError();
            }
        });
    }
}