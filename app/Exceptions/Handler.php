<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;
use App\Http\Traits\PjaxTrait;


class Handler extends ExceptionHandler
{

    use PjaxTrait;

    public function register()
    {
        // Handle 404 Not Found
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->ajax() || $request->header('X-PJAX')) {
                return response()->json([
                    'html' => view('errors.404')->render(),
                    'url' => $request->url(),
                    'title' => 'Page Not Found'
                ], 404);
            }

            if ($request->is('api/*')) {
                return response()->json(['message' => 'Resource not found'], 404);
            }
            return response()->view('errors.404', [], 404);
        });

        // Handle 403 Access Denied
        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->ajax() || $request->header('X-PJAX')) {
                return response()->json([
                    'html' => view('errors.403')->render(),
                    'url' => $request->url(),
                    'title' => 'Access Denied'
                ], 403);
            }

            if ($request->is('api/*')) {
                return response()->json(['message' => 'Access denied'], 403);
            }
            return response()->view('errors.403', [], 403);
        });

        // Handle 500 Server Error
        $this->renderable(function (Throwable $e, $request) {
            if ($this->isHttpException($e) && $e->getStatusCode() == 500) {
                if ($request->ajax() || $request->header('X-PJAX')) {
                    return response()->json([
                        'html' => view('errors.500')->render(),
                        'url' => $request->url(),
                        'title' => 'Server Error'
                    ], 500);
                }

                if ($request->is('api/*')) {
                    return response()->json(['message' => 'Server error'], 500);
                }
                return response()->view('errors.500', [], 500);
            }

            // Optionally, you could handle other types of exceptions here
        });
    }
}
