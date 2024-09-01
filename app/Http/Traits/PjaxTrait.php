<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\View;

trait PjaxTrait
{
    protected function view($view, $data = [], $status = 200, $layout = null)
    {
        // Ensure the layout is passed to the view data
        $data = array_merge($data, ['layout' => $layout]);

        $content = view($view, $data)->render();
        $definedLayout = $GLOBALS['__pjaxLayout'] ?? $layout ?? 'layouts.auth';

        // Render the layout view with the content passed as a variable
        $layoutView = view($definedLayout, array_merge($data, ['content' => $content]));

        if (request()->ajax() || request()->header('X-PJAX')) {
            $layoutContent = $layoutView->render();
            $title = $this->extractTitle($layoutContent);

            return new JsonResponse([
                'html' => $content,
                'title' => $title,
                'layout' => $definedLayout,
                'metaDescription' => $this->extractMetaDescription($layoutContent),
                'canonicalUrl' => $this->extractCanonicalUrl($layoutContent)
            ], $status);
        }

        return response($layoutView, $status);
    }

    private function extractTitle($content)
    {
        preg_match('/<title>(.*?)<\/title>/i', $content, $matches);
        return $matches[1] ?? config('app.name');
    }

    private function extractMetaDescription($content)
    {
        preg_match('/<meta name="description" content="(.*?)"/i', $content, $matches);
        return $matches[1] ?? '';
    }

    private function extractCanonicalUrl($content)
    {
        preg_match('/<link rel="canonical" href="(.*?)"/i', $content, $matches);
        return $matches[1] ?? request()->url();
    }
}


